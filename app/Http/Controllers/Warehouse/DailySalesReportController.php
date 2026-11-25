<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Product;
use App\Models\DriverProduct;
use App\Models\DriverProductDetails;
use App\Models\DriverWarehouse;
use App\Models\Invoice;
use App\Models\InvoiceData;
use App\Models\WarehouseStock;
use App\Models\DriverReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DailySalesReportController extends Controller
{
    public function index(Request $request)
    {
        $warehouse_id = Auth::guard('backend')->user()->id;
        $reportDate = $request->filled('date') ? $request->date : date('Y-m-d');
        $selectedDriverId = $request->filled('driver_id') ? (int)$request->driver_id : null;

        // Fetch drivers strictly assigned to this warehouse ONLY
        $warehouseDrivers = DriverWarehouse::join('admins', 'driver_warehouse.driver_id', '=', 'admins.id')
            ->where('driver_warehouse.warehouse_id', $warehouse_id)
            ->where('admins.is_hidden', 0)
            ->select(['admins.id', 'admins.name'])
            ->orderBy('admins.name', 'asc')
            ->get();

        $driversToProcess = $warehouseDrivers;
        if ($selectedDriverId) {
            $driversToProcess = $warehouseDrivers->filter(function ($d) use ($selectedDriverId) {
                return (int)$d->id === (int)$selectedDriverId;
            });
        }

        // Fetch all active products for reference & normalization
        $products = Product::where('status', '1')->get()->keyBy('id');

        $driverReports = [];
        $overallSalesAmount = 0;
        $overallTotalInvoices = 0;
        $overallAssignedBottles = 0;
        $overallCarryForwardBottles = 0;
        $overallReturnedBottles = 0;
        $overallSoldBottles = 0;
        $overallAssignedCases = 0;
        $overallAssignedLoose = 0;
        $overallCarryForwardCases = 0;
        $overallCarryForwardLoose = 0;
        $overallReturnedCases = 0;
        $overallReturnedLoose = 0;
        $overallSoldCases = 0;
        $overallSoldLoose = 0;
        $activeDriversCount = 0;

        foreach ($driversToProcess as $driver) {
            $did = $driver->id;

            // 1. Assigned Quantities on this date
            $assignedRows = DB::table('driver_products')
                ->join('driver_product_details', 'driver_products.id', '=', 'driver_product_details.driver_products_id')
                ->where('driver_products.warehouse_id', $warehouse_id)
                ->where('driver_products.driver_id', $did)
                ->where(function ($q) use ($reportDate) {
                    $q->whereDate('driver_products.assign_date', $reportDate)
                      ->orWhere(function ($sub) use ($reportDate) {
                          $sub->whereNull('driver_products.assign_date')
                              ->whereDate('driver_products.created_at', $reportDate);
                      });
                })
                ->select([
                    'driver_product_details.product_id',
                    DB::raw('SUM(driver_product_details.quantity) as total_cases'),
                    DB::raw('SUM(driver_product_details.bottles) as total_bottles')
                ])
                ->groupBy('driver_product_details.product_id')
                ->get()
                ->keyBy('product_id');

            // 2. Sold Quantities & Invoices on this date
            $invoices = Invoice::where('driver_id', $did)
                ->whereDate('created_at', $reportDate)
                ->get();

            $driverSalesAmount = (float)$invoices->sum('total_price');
            $driverInvoicesCount = $invoices->count();

            $soldRows = DB::table('invoice')
                ->join('invoice_data', 'invoice.id', '=', 'invoice_data.invoice_id')
                ->where('invoice.driver_id', $did)
                ->whereDate('invoice.created_at', $reportDate)
                ->select([
                    'invoice_data.product_id',
                    DB::raw('SUM(invoice_data.quantity) as sold_bottles')
                ])
                ->groupBy('invoice_data.product_id')
                ->get()
                ->keyBy('product_id');

            // 3. Manual Recorded Returns (if any)
            $manualReturns = DriverReturn::where('warehouse_id', $warehouse_id)
                ->where('driver_id', $did)
                ->whereDate('return_date', $reportDate)
                ->get();
            $hasManualReturn = $manualReturns->isNotEmpty();

            // Group returns per product into Carry Forward vs Return
            $productReturns = [];
            foreach ($manualReturns as $mRet) {
                $pid = (int)$mRet->product_id;
                if (!isset($productReturns[$pid])) {
                    $productReturns[$pid] = [
                        'cf_cases' => 0,
                        'cf_bottles' => 0,
                        'ret_cases' => 0,
                        'ret_bottles' => 0,
                    ];
                }
                $c = (int)$mRet->cases;
                $b = (int)$mRet->bottles;
                if ((int)$mRet->is_carry_forward === 1) {
                    $productReturns[$pid]['cf_cases'] += $c;
                    $productReturns[$pid]['cf_bottles'] += $b;
                } else {
                    $productReturns[$pid]['ret_cases'] += $c;
                    $productReturns[$pid]['ret_bottles'] += $b;
                }
            }

            // Calculate driver totals across all relevant products
            $allProductIds = $assignedRows->keys()
                ->merge($soldRows->keys())
                ->merge(array_keys($productReturns))
                ->unique();

            $driverAssignedTotalBottles = 0;
            $driverCarryForwardTotalBottles = 0;
            $driverReturnedTotalBottles = 0;
            $driverSoldTotalBottles = 0;
            $calculatedDriverSalesAmount = 0;

            $driverAssignedCases = 0;
            $driverAssignedLoose = 0;
            $driverCarryForwardCases = 0;
            $driverCarryForwardLoose = 0;
            $driverReturnedCases = 0;
            $driverReturnedLoose = 0;
            $driverSoldCases = 0;
            $driverSoldLoose = 0;

            foreach ($allProductIds as $pid) {
                $p = $products->get($pid) ?? Product::find($pid);
                if (!$p) continue;

                $bpc = ($p->per_case_quantity > 0) ? (int)$p->per_case_quantity : 24;
                $bottlePrice = (float)($p->per_bottle_price ?? 0);

                // Assigned
                $aRow = $assignedRows->get($pid);
                $aCases = $aRow ? (int)$aRow->total_cases : 0;
                $aLoose = $aRow ? (int)$aRow->total_bottles : 0;
                $pAssignedBottles = ($aCases * $bpc) + $aLoose;
                $driverAssignedTotalBottles += $pAssignedBottles;

                $driverAssignedCases += $aCases;
                $driverAssignedLoose += $aLoose;

                // Carry Forward & Returned
                $cfCases = 0;
                $cfLoose = 0;
                $rCases = 0;
                $rLoose = 0;
                if ($hasManualReturn && isset($productReturns[$pid])) {
                    $pRetData = $productReturns[$pid];
                    $cfCases = (int)$pRetData['cf_cases'];
                    $cfLoose = (int)$pRetData['cf_bottles'];
                    $rCases = (int)$pRetData['ret_cases'];
                    $rLoose = (int)$pRetData['ret_bottles'];
                }
                $pCfBottles = ($cfCases * $bpc) + $cfLoose;
                $pRetBottles = ($rCases * $bpc) + $rLoose;

                $driverCarryForwardCases += $cfCases;
                $driverCarryForwardLoose += $cfLoose;
                $driverReturnedCases += $rCases;
                $driverReturnedLoose += $rLoose;

                $driverCarryForwardTotalBottles += $pCfBottles;
                $driverReturnedTotalBottles += $pRetBottles;
                $pUnsoldBottles = $pCfBottles + $pRetBottles;

                // Sold: When return is entered, Sold Quantity = Assigned Quantity - (Carry Forward + Returned Quantity)
                $sRow = $soldRows->get($pid);
                $invSoldBottles = $sRow ? (int)$sRow->sold_bottles : 0;

                if ($hasManualReturn) {
                    $pSoldBottles = max(0, $pAssignedBottles - $pUnsoldBottles);
                    if ($pSoldBottles == 0 && $pAssignedBottles == 0 && $invSoldBottles > 0) {
                        $pSoldBottles = $invSoldBottles;
                    }
                } else {
                    $pSoldBottles = $invSoldBottles;
                }
                $driverSoldTotalBottles += $pSoldBottles;

                $sCases = intdiv($pSoldBottles, $bpc);
                $sLoose = $pSoldBottles % $bpc;

                $driverSoldCases += $sCases;
                $driverSoldLoose += $sLoose;

                $calculatedDriverSalesAmount += ($pSoldBottles * $bottlePrice);
            }

            // Normalization helper matching breakdown
            $assignedText = $this->formatStockText($driverAssignedCases, $driverAssignedLoose);
            $carryForwardText = $this->formatStockText($driverCarryForwardCases, $driverCarryForwardLoose);
            $returnedText = $this->formatStockText($driverReturnedCases, $driverReturnedLoose);
            $soldText = $this->formatStockText($driverSoldCases, $driverSoldLoose);

            if ($hasManualReturn) {
                $driverSalesAmount = $calculatedDriverSalesAmount;
            } else {
                $driverSalesAmount = ($driverSalesAmount > 0) ? $driverSalesAmount : $calculatedDriverSalesAmount;
            }

            $isActive = ($driverAssignedTotalBottles > 0 || $driverSoldTotalBottles > 0 || $driverSalesAmount > 0 || $driverInvoicesCount > 0 || $driverCarryForwardTotalBottles > 0 || $driverReturnedTotalBottles > 0);

            if ($isActive) {
                $activeDriversCount++;
            }

            $driverReports[] = [
                'driver_id' => $driver->id,
                'driver_name' => $driver->name,
                'assigned_bottles' => $driverAssignedTotalBottles,
                'assigned_text' => $assignedText,
                'carry_forward_bottles' => $driverCarryForwardTotalBottles,
                'carry_forward_text' => $carryForwardText,
                'returned_bottles' => $driverReturnedTotalBottles,
                'returned_text' => $returnedText,
                'sold_bottles' => $driverSoldTotalBottles,
                'sold_text' => $soldText,
                'has_manual_return' => $hasManualReturn,
                'sales_amount' => $driverSalesAmount,
                'invoices_count' => $driverInvoicesCount,
                'is_active' => $isActive,
            ];

            $overallSalesAmount += $driverSalesAmount;
            $overallTotalInvoices += $driverInvoicesCount;
            $overallAssignedBottles += $driverAssignedTotalBottles;
            $overallCarryForwardBottles += $driverCarryForwardTotalBottles;
            $overallReturnedBottles += $driverReturnedTotalBottles;
            $overallSoldBottles += $driverSoldTotalBottles;

            $overallAssignedCases += $driverAssignedCases;
            $overallAssignedLoose += $driverAssignedLoose;
            $overallCarryForwardCases += $driverCarryForwardCases;
            $overallCarryForwardLoose += $driverCarryForwardLoose;
            $overallReturnedCases += $driverReturnedCases;
            $overallReturnedLoose += $driverReturnedLoose;
            $overallSoldCases += $driverSoldCases;
            $overallSoldLoose += $driverSoldLoose;
        }

        // Sort: Active drivers first, then by sales amount descending
        usort($driverReports, function ($a, $b) {
            if ($a['is_active'] !== $b['is_active']) {
                return $b['is_active'] <=> $a['is_active'];
            }
            return $b['sales_amount'] <=> $a['sales_amount'];
        });

        $overallAssignedText = $this->formatStockText($overallAssignedCases, $overallAssignedLoose);
        $overallCarryForwardText = $this->formatStockText($overallCarryForwardCases, $overallCarryForwardLoose);
        $overallReturnedText = $this->formatStockText($overallReturnedCases, $overallReturnedLoose);
        $overallSoldText = $this->formatStockText($overallSoldCases, $overallSoldLoose);

        return view('warehouse.daily_sales_report.index', compact([
            'reportDate',
            'warehouseDrivers',
            'selectedDriverId',
            'driverReports',
            'overallSalesAmount',
            'overallTotalInvoices',
            'overallAssignedText',
            'overallCarryForwardText',
            'overallReturnedText',
            'overallSoldText',
            'activeDriversCount',
            'products'
        ]));
    }

    public function driverBreakdown(Request $request, $driver_id, $date)
    {
        $warehouse_id = Auth::guard('backend')->user()->id;

        // Security check: verify driver strictly belongs to this warehouse
        $isDriverAssigned = DriverWarehouse::where('warehouse_id', $warehouse_id)
            ->where('driver_id', $driver_id)
            ->exists();
        if (!$isDriverAssigned) {
            return response()->json(['status' => 403, 'message' => 'Driver is not assigned to your warehouse.'], 403);
        }

        $driver = Admin::find($driver_id);
        if (!$driver) {
            return response()->json(['status' => 404, 'message' => 'Driver not found.'], 404);
        }

        $products = Product::where('status', '1')->get()->keyBy('id');

        // 1. Assigned on this date
        $assignedRows = DB::table('driver_products')
            ->join('driver_product_details', 'driver_products.id', '=', 'driver_product_details.driver_products_id')
            ->where('driver_products.warehouse_id', $warehouse_id)
            ->where('driver_products.driver_id', $driver_id)
            ->where(function ($q) use ($date) {
                $q->whereDate('driver_products.assign_date', $date)
                  ->orWhere(function ($sub) use ($date) {
                      $sub->whereNull('driver_products.assign_date')
                          ->whereDate('driver_products.created_at', $date);
                  });
            })
            ->select([
                'driver_product_details.product_id',
                DB::raw('SUM(driver_product_details.quantity) as total_cases'),
                DB::raw('SUM(driver_product_details.bottles) as total_bottles')
            ])
            ->groupBy('driver_product_details.product_id')
            ->get()
            ->keyBy('product_id');

        // 2. Sold on this date
        $soldRows = DB::table('invoice')
            ->join('invoice_data', 'invoice.id', '=', 'invoice_data.invoice_id')
            ->where('invoice.driver_id', $driver_id)
            ->whereDate('invoice.created_at', $date)
            ->select([
                'invoice_data.product_id',
                DB::raw('SUM(invoice_data.quantity) as sold_bottles')
            ])
            ->groupBy('invoice_data.product_id')
            ->get()
            ->keyBy('product_id');

        // 3. Manual returns (if any)
        $manualReturns = DriverReturn::where('warehouse_id', $warehouse_id)
            ->where('driver_id', $driver_id)
            ->whereDate('return_date', $date)
            ->get();

        $productReturns = [];
        foreach ($manualReturns as $mRet) {
            $pid = (int)$mRet->product_id;
            if (!isset($productReturns[$pid])) {
                $productReturns[$pid] = [
                    'cf_cases' => 0,
                    'cf_bottles' => 0,
                    'ret_cases' => 0,
                    'ret_bottles' => 0,
                ];
            }
            $c = (int)$mRet->cases;
            $b = (int)$mRet->bottles;
            if ((int)$mRet->is_carry_forward === 1) {
                $productReturns[$pid]['cf_cases'] += $c;
                $productReturns[$pid]['cf_bottles'] += $b;
            } else {
                $productReturns[$pid]['ret_cases'] += $c;
                $productReturns[$pid]['ret_bottles'] += $b;
            }
        }

        $allProductIds = $assignedRows->keys()
            ->merge($soldRows->keys())
            ->merge(array_keys($productReturns))
            ->unique();

        $items = [];
        $totalAssignedCases = 0;
        $totalAssignedBottles = 0;
        $totalCarryForwardCases = 0;
        $totalCarryForwardBottles = 0;
        $totalReturnedCases = 0;
        $totalReturnedBottles = 0;
        $totalSoldCases = 0;
        $totalSoldBottles = 0;
        $totalSalesAmount = 0;

        $hasManualReturn = $manualReturns->isNotEmpty();

        foreach ($allProductIds as $pid) {
            $p = $products->get($pid) ?? Product::find($pid);
            if (!$p) continue;

            $bpc = ($p->per_case_quantity > 0) ? (int)$p->per_case_quantity : 24;
            $bottlePrice = (float)($p->per_bottle_price ?? 0);

            // Assigned
            $aRow = $assignedRows->get($pid);
            $aCases = $aRow ? (int)$aRow->total_cases : 0;
            $aLoose = $aRow ? (int)$aRow->total_bottles : 0;
            $totalAssignedB = ($aCases * $bpc) + $aLoose;

            // Carry Forward & Returned
            if (isset($productReturns[$pid])) {
                $pRet = $productReturns[$pid];
                $cfCases = $pRet['cf_cases'];
                $cfLoose = $pRet['cf_bottles'];
                $rCases = $pRet['ret_cases'];
                $rLoose = $pRet['ret_bottles'];
            } else {
                $cfCases = 0;
                $cfLoose = 0;
                $rCases = 0;
                $rLoose = 0;
            }
            $totalCfB = ($cfCases * $bpc) + $cfLoose;
            $totalRetB = ($rCases * $bpc) + $rLoose;
            $totalUnsoldB = $totalCfB + $totalRetB;

            // Sold: When return is entered, Sold Quantity = Assigned Quantity - (Carry Forward + Returned Quantity)
            $sRow = $soldRows->get($pid);
            $invBottles = $sRow ? (int)$sRow->sold_bottles : 0;

            if ($hasManualReturn) {
                $sBottles = max(0, $totalAssignedB - $totalUnsoldB);
                if ($sBottles == 0 && $totalAssignedB == 0 && $invBottles > 0) {
                    $sBottles = $invBottles;
                }
            } else {
                $sBottles = $invBottles;
            }

            $sCases = intdiv($sBottles, $bpc);
            $sLoose = $sBottles % $bpc;

            $itemRevenue = $sBottles * $bottlePrice;
            $totalSalesAmount += $itemRevenue;

            $totalAssignedCases += $aCases;
            $totalAssignedBottles += $aLoose;
            $totalCarryForwardCases += $cfCases;
            $totalCarryForwardBottles += $cfLoose;
            $totalReturnedCases += $rCases;
            $totalReturnedBottles += $rLoose;
            $totalSoldCases += $sCases;
            $totalSoldBottles += $sLoose;

            $items[] = [
                'product_id' => $p->id,
                'product_name' => $p->name,
                'bottles_per_case' => $bpc,
                'assigned_cases' => $aCases,
                'assigned_bottles' => $aLoose,
                'assigned_text' => $this->formatStockText($aCases, $aLoose),
                'carry_forward_cases' => $cfCases,
                'carry_forward_bottles' => $cfLoose,
                'carry_forward_text' => $this->formatStockText($cfCases, $cfLoose),
                'returned_cases' => $rCases,
                'returned_bottles' => $rLoose,
                'returned_text' => $this->formatStockText($rCases, $rLoose),
                'sold_cases' => $sCases,
                'sold_bottles' => $sLoose,
                'sold_text' => $this->formatStockText($sCases, $sLoose),
                'sales_amount' => number_format($itemRevenue, 2),
            ];
        }

        return response()->json([
            'status' => 200,
            'driver_name' => $driver->name,
            'report_date' => date('d F, Y', strtotime($date)),
            'items' => $items,
            'totals' => [
                'assigned_text' => $this->formatStockText($totalAssignedCases, $totalAssignedBottles),
                'carry_forward_text' => $this->formatStockText($totalCarryForwardCases, $totalCarryForwardBottles),
                'returned_text' => $this->formatStockText($totalReturnedCases, $totalReturnedBottles),
                'sold_text' => $this->formatStockText($totalSoldCases, $totalSoldBottles),
                'sales_amount' => '₹ ' . number_format($totalSalesAmount, 2),
            ]
        ]);
    }

    public function getDriverReturns(Request $request, $driver_id, $date)
    {
        $warehouse_id = Auth::guard('backend')->user()->id;

        // Security check: verify driver strictly belongs to this warehouse
        $isDriverAssigned = DriverWarehouse::where('warehouse_id', $warehouse_id)
            ->where('driver_id', $driver_id)
            ->exists();
        if (!$isDriverAssigned) {
            return response()->json(['status' => 403, 'message' => 'Driver is not assigned to your warehouse.'], 403);
        }

        // 1. Fetch products assigned to this driver on this date
        $assignedRows = DB::table('driver_products')
            ->join('driver_product_details', 'driver_products.id', '=', 'driver_product_details.driver_products_id')
            ->join('products', 'driver_product_details.product_id', '=', 'products.id')
            ->where('driver_products.driver_id', $driver_id)
            ->where(function ($q) use ($date) {
                $q->whereDate('driver_products.assign_date', $date)
                  ->orWhere(function ($sub) use ($date) {
                      $sub->whereNull('driver_products.assign_date')
                          ->whereDate('driver_products.created_at', $date);
                  });
            })
            ->select([
                'driver_product_details.product_id',
                'products.name as product_name',
                'products.per_case_quantity',
                DB::raw('SUM(driver_product_details.quantity) as total_cases'),
                DB::raw('SUM(driver_product_details.bottles) as total_bottles')
            ])
            ->groupBy('driver_product_details.product_id', 'products.name', 'products.per_case_quantity')
            ->get()
            ->keyBy('product_id');

        // 2. Fetch recorded returns for this driver on this date (if any)
        $recordedReturns = DriverReturn::where('warehouse_id', $warehouse_id)
            ->where('driver_id', $driver_id)
            ->whereDate('return_date', $date)
            ->get(['product_id', 'cases', 'bottles', 'is_carry_forward', 'is_return', 'notes']);

        $notes = $recordedReturns->isNotEmpty() ? ($recordedReturns->first()->notes ?? '') : '';

        $rows = [];
        $loadedProductIds = [];
        if ($recordedReturns->isNotEmpty()) {
            foreach ($recordedReturns as $rRow) {
                $pid = $rRow->product_id;
                $product = Product::find($pid);
                if (!$product) continue;

                $aRow = $assignedRows->get($pid);
                $aCases = $aRow ? (int)$aRow->total_cases : 0;
                $aBottles = $aRow ? (int)$aRow->total_bottles : 0;
                $assignedText = $this->formatStockText($aCases, $aBottles);

                $rows[] = [
                    'product_id'        => $product->id,
                    'product_name'      => $product->name,
                    'assigned_cases'    => $aCases,
                    'assigned_bottles'  => $aBottles,
                    'assigned_text'     => $assignedText,
                    'cases'             => (int)$rRow->cases,
                    'bottles'           => (int)$rRow->bottles,
                    'is_carry_forward'  => (int)$rRow->is_carry_forward === 1,
                    'is_return'         => (int)$rRow->is_return === 1,
                    'has_return'        => true,
                ];
                $loadedProductIds[] = $pid;
            }
        }

        // Also add any assigned products that were not in recorded returns
        foreach ($assignedRows as $pid => $aRow) {
            if (!in_array($pid, $loadedProductIds)) {
                $product = Product::find($pid);
                if (!$product) continue;

                $rows[] = [
                    'product_id'        => $product->id,
                    'product_name'      => $product->name,
                    'assigned_cases'    => (int)$aRow->total_cases,
                    'assigned_bottles'  => (int)$aRow->total_bottles,
                    'assigned_text'     => $this->formatStockText($aRow->total_cases, $aRow->total_bottles),
                    'cases'             => 0,
                    'bottles'           => 0,
                    'is_carry_forward'  => false,
                    'is_return'         => false,
                    'has_return'        => false,
                ];
            }
        }

        $assignedProductsList = [];
        foreach ($assignedRows as $pid => $item) {
            $assignedProductsList[] = [
                'product_id'    => $item->product_id,
                'product_name'  => $item->product_name,
                'assigned_text' => $this->formatStockText($item->total_cases, $item->total_bottles),
            ];
        }

        return response()->json([
            'status'            => 200,
            'returns'           => $rows,
            'assigned_products' => $assignedProductsList,
            'has_recorded'      => $recordedReturns->isNotEmpty(),
            'notes'             => $notes,
        ]);
    }

    public function storeReturn(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:admins,id',
            'return_date' => 'required|date',
            'returns' => 'nullable|array',
            'returns.*.product_id' => 'nullable|exists:products,id',
            'returns.*.cases' => 'nullable|integer|min:0',
            'returns.*.bottles' => 'nullable|integer|min:0',
            'returns.*.is_carry_forward' => 'nullable',
            'returns.*.is_return' => 'nullable',
            'notes' => 'nullable|string',
        ]);

        $warehouse_id = Auth::guard('backend')->user()->id;
        $driver_id = (int)$request->driver_id;
        $return_date = $request->return_date;

        // Security check: verify driver strictly belongs to this warehouse
        $isDriverAssigned = DriverWarehouse::where('warehouse_id', $warehouse_id)
            ->where('driver_id', $driver_id)
            ->exists();
        if (!$isDriverAssigned) {
            return redirect()->back()->with('error_msg', 'Selected driver is not assigned to your warehouse.');
        }

        DB::beginTransaction();
        try {
            $nextDate = date('Y-m-d', strtotime($return_date . ' +1 day'));

            // Revert previously restocked items for this return_date if any (in case of re-saving returns)
            $oldReturns = DriverReturn::where('warehouse_id', $warehouse_id)
                ->where('driver_id', $driver_id)
                ->whereDate('return_date', $return_date)
                ->get();

            foreach ($oldReturns as $oldRet) {
                if ((int)$oldRet->is_return === 1) {
                    $this->deductWarehouseStock($warehouse_id, (int)$oldRet->product_id, (int)$oldRet->cases, (int)$oldRet->bottles);
                }
            }

            // Delete old returns for this driver on this date
            DriverReturn::where('warehouse_id', $warehouse_id)
                ->where('driver_id', $driver_id)
                ->whereDate('return_date', $return_date)
                ->delete();

            $carryForwardItems = [];

            if ($request->has('returns') && is_array($request->returns)) {
                $grouped = [];
                foreach ($request->returns as $retItem) {
                    if (empty($retItem['product_id'])) continue;
                    $pid = (int)$retItem['product_id'];
                    $c = (int)($retItem['cases'] ?? 0);
                    $b = (int)($retItem['bottles'] ?? 0);
                    $isCarryForward = !empty($retItem['is_carry_forward']);
                    $isReturn = !empty($retItem['is_return']);

                    $typeKey = $isCarryForward ? 'cf' : ($isReturn ? 'ret' : 'default');
                    $groupKey = $pid . '_' . $typeKey;

                    if (!isset($grouped[$groupKey])) {
                        $grouped[$groupKey] = [
                            'product_id' => $pid,
                            'cases' => 0,
                            'bottles' => 0,
                            'is_carry_forward' => $isCarryForward ? 1 : 0,
                            'is_return' => $isReturn ? 1 : 0,
                        ];
                    }
                    $grouped[$groupKey]['cases'] += $c;
                    $grouped[$groupKey]['bottles'] += $b;
                }

                foreach ($grouped as $qty) {
                    $c = $qty['cases'];
                    $b = $qty['bottles'];
                    $pid = $qty['product_id'];
                    if ($c > 0 || $b > 0) {
                        DriverReturn::create([
                            'warehouse_id' => $warehouse_id,
                            'driver_id' => $driver_id,
                            'return_date' => $return_date,
                            'product_id' => $pid,
                            'cases' => $c,
                            'bottles' => $b,
                            'is_carry_forward' => $qty['is_carry_forward'],
                            'is_return' => $qty['is_return'],
                            'notes' => $request->notes,
                        ]);

                        // If return is true, add physically returned items back into warehouse stock
                        if (!empty($qty['is_return'])) {
                            $this->restockWarehouse($warehouse_id, $pid, $c, $b);
                        }

                        // If carry forward is true, warehouse stock will NOT be updated. Collect for direct next day assignment.
                        if (!empty($qty['is_carry_forward'])) {
                            $carryForwardItems[] = [
                                'product_id' => $pid,
                                'cases' => $c,
                                'bottles' => $b,
                            ];
                        }
                    }
                }
            }

            // Directly assign carry forward products to driver for next day without deducting warehouse stock
            $nextDateDp = DriverProduct::where('warehouse_id', $warehouse_id)
                ->where('driver_id', $driver_id)
                ->whereDate('assign_date', $nextDate)
                ->first();

            if (!empty($carryForwardItems)) {
                if (!$nextDateDp) {
                    $nextDateDp = DriverProduct::create([
                        'driver_id' => $driver_id,
                        'warehouse_id' => $warehouse_id,
                        'total_products' => count($carryForwardItems),
                        'assign_date' => $nextDate,
                        'grand_total' => 'carry_forward',
                    ]);
                    foreach ($carryForwardItems as $item) {
                        DriverProductDetails::create([
                            'driver_products_id' => $nextDateDp->id,
                            'driver_id' => $driver_id,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['cases'],
                            'bottles' => $item['bottles'],
                        ]);
                    }
                } else {
                    if ($nextDateDp->grand_total === 'carry_forward') {
                        DriverProductDetails::where('driver_products_id', $nextDateDp->id)->delete();
                        foreach ($carryForwardItems as $item) {
                            DriverProductDetails::create([
                                'driver_products_id' => $nextDateDp->id,
                                'driver_id' => $driver_id,
                                'product_id' => $item['product_id'],
                                'quantity' => $item['cases'],
                                'bottles' => $item['bottles'],
                            ]);
                        }
                        $nextDateDp->update(['total_products' => count($carryForwardItems)]);
                    } else {
                        // Already exists: update or sync the carry forward products
                        foreach ($carryForwardItems as $item) {
                            $existingDetail = DriverProductDetails::where('driver_products_id', $nextDateDp->id)
                                ->where('product_id', $item['product_id'])
                                ->first();
                            if ($existingDetail) {
                                $existingDetail->update([
                                    'quantity' => $item['cases'],
                                    'bottles' => $item['bottles'],
                                ]);
                            } else {
                                DriverProductDetails::create([
                                    'driver_products_id' => $nextDateDp->id,
                                    'driver_id' => $driver_id,
                                    'product_id' => $item['product_id'],
                                    'quantity' => $item['cases'],
                                    'bottles' => $item['bottles'],
                                ]);
                            }
                        }
                        $nextDateDp->update([
                            'total_products' => DriverProductDetails::where('driver_products_id', $nextDateDp->id)->count()
                        ]);
                    }
                }
            } elseif ($nextDateDp && $nextDateDp->grand_total === 'carry_forward') {
                // If carry forward items were removed on re-save, clean up auto-assigned record
                DriverProductDetails::where('driver_products_id', $nextDateDp->id)->delete();
                $nextDateDp->delete();
            }

            DB::commit();
            return redirect()->back()->withSuccess('Driver returned stock recorded successfully. Carry forward products directly assigned to driver for next day.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error_msg', 'Failed to record return: ' . $e->getMessage());
        }
    }

    private function restockWarehouse($warehouse_id, $product_id, $cases, $bottles)
    {
        $productStock = WarehouseStock::where('warehouse_id', $warehouse_id)
            ->where('product_id', $product_id)
            ->first();

        $bottlesPerCase = (int)Product::where('id', $product_id)->value('per_case_quantity');

        if ($productStock) {
            if ($bottlesPerCase > 0) {
                $curr = ((int)$productStock->stock * $bottlesPerCase) + (int)$productStock->bottles;
                $add = ($cases * $bottlesPerCase) + $bottles;
                $newTotal = $curr + $add;
                $newStock = intdiv($newTotal, $bottlesPerCase);
                $newBottles = $newTotal % $bottlesPerCase;
            } else {
                $newStock = (int)$productStock->stock + $cases;
                $newBottles = (int)$productStock->bottles + $bottles;
            }

            WarehouseStock::where('id', $productStock->id)->update([
                'stock' => $newStock,
                'bottles' => $newBottles,
            ]);
        } else {
            WarehouseStock::create([
                'warehouse_id' => $warehouse_id,
                'product_id' => $product_id,
                'stock' => $cases,
                'bottles' => $bottles,
            ]);
        }
    }

    private function deductWarehouseStock($warehouse_id, $product_id, $cases, $bottles)
    {
        $productStock = WarehouseStock::where('warehouse_id', $warehouse_id)
            ->where('product_id', $product_id)
            ->first();

        if ($productStock) {
            $bottlesPerCase = (int)Product::where('id', $product_id)->value('per_case_quantity') ?: 24;
            $curr = ((int)$productStock->stock * $bottlesPerCase) + (int)$productStock->bottles;
            $deduct = ($cases * $bottlesPerCase) + $bottles;
            $newTotal = max(0, $curr - $deduct);
            $newStock = intdiv($newTotal, $bottlesPerCase);
            $newBottles = $newTotal % $bottlesPerCase;

            WarehouseStock::where('id', $productStock->id)->update([
                'stock' => $newStock,
                'bottles' => $newBottles,
            ]);
        }
    }

    private function bottlesToCaseText($totalBottles, $bpc = 24)
    {
        $totalBottles = (int)$totalBottles;
        $bpc = (int)$bpc;
        if ($totalBottles <= 0) {
            return "0 Cases";
        }
        $cases = intdiv($totalBottles, $bpc);
        $loose = $totalBottles % $bpc;

        if ($cases > 0 && $loose > 0) {
            return "{$cases} Cases + {$loose} Bottles";
        } elseif ($cases > 0) {
            return "{$cases} Cases";
        }
        return "{$loose} Bottles";
    }

    private function formatStockText($cases, $bottles)
    {
        $cases = (int)$cases;
        $bottles = (int)$bottles;
        if ($cases > 0 && $bottles > 0) {
            return "{$cases} Cases + {$bottles} Bottles";
        } elseif ($cases > 0) {
            return "{$cases} Cases";
        } elseif ($bottles > 0) {
            return "{$bottles} Bottles";
        }
        return "0 Cases";
    }
}
