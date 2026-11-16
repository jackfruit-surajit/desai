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

        $driversToProcess = $selectedDriverId 
            ? $warehouseDrivers->filter(fn($d) => (int)$d->id === $selectedDriverId)
            : $warehouseDrivers;

        // Fetch all active products for reference & normalization
        $products = Product::where('status', '1')->get()->keyBy('id');

        $driverReports = [];
        $overallSalesAmount = 0;
        $overallTotalInvoices = 0;
        $overallAssignedBottles = 0;
        $overallSoldBottles = 0;
        $overallReturnedBottles = 0;
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
                ->get()
                ->keyBy('product_id');
            $hasManualReturn = $manualReturns->isNotEmpty();

            // Calculate driver totals across all relevant products
            $allProductIds = $assignedRows->keys()
                ->merge($soldRows->keys())
                ->merge($manualReturns->keys())
                ->unique();

            $driverAssignedTotalBottles = 0;
            $driverSoldTotalBottles = 0;
            $driverReturnedTotalBottles = 0;

            foreach ($allProductIds as $pid) {
                $p = $products->get($pid) ?? Product::find($pid);
                $bpc = ($p && $p->per_case_quantity > 0) ? (int)$p->per_case_quantity : 24;

                // Assigned
                $aRow = $assignedRows->get($pid);
                $aCases = $aRow ? (int)$aRow->total_cases : 0;
                $aLoose = $aRow ? (int)$aRow->total_bottles : 0;
                $pAssignedBottles = ($aCases * $bpc) + $aLoose;
                $driverAssignedTotalBottles += $pAssignedBottles;

                // Sold
                $sRow = $soldRows->get($pid);
                $pSoldBottles = $sRow ? (int)$sRow->sold_bottles : 0;
                $driverSoldTotalBottles += $pSoldBottles;

                // Returned
                if ($hasManualReturn && $manualReturns->has($pid)) {
                    $mRet = $manualReturns->get($pid);
                    $rCases = (int)$mRet->cases;
                    $rLoose = (int)$mRet->bottles;
                    $pReturnedBottles = ($rCases * $bpc) + $rLoose;
                } else {
                    $pReturnedBottles = max(0, $pAssignedBottles - $pSoldBottles);
                }
                $driverReturnedTotalBottles += $pReturnedBottles;
            }

            // Normalization helper
            $assignedText = $this->bottlesToCaseText($driverAssignedTotalBottles);
            $soldText = $this->bottlesToCaseText($driverSoldTotalBottles);
            $returnedText = $this->bottlesToCaseText($driverReturnedTotalBottles);

            $isActive = ($driverAssignedTotalBottles > 0 || $driverSoldTotalBottles > 0 || $driverSalesAmount > 0 || $driverInvoicesCount > 0);

            if ($isActive) {
                $activeDriversCount++;
            }

            $driverReports[] = [
                'driver_id' => $driver->id,
                'driver_name' => $driver->name,
                'assigned_bottles' => $driverAssignedTotalBottles,
                'assigned_text' => $assignedText,
                'sold_bottles' => $driverSoldTotalBottles,
                'sold_text' => $soldText,
                'returned_bottles' => $driverReturnedTotalBottles,
                'returned_text' => $returnedText,
                'has_manual_return' => $hasManualReturn,
                'sales_amount' => $driverSalesAmount,
                'invoices_count' => $driverInvoicesCount,
                'is_active' => $isActive,
            ];

            $overallSalesAmount += $driverSalesAmount;
            $overallTotalInvoices += $driverInvoicesCount;
            $overallAssignedBottles += $driverAssignedTotalBottles;
            $overallSoldBottles += $driverSoldTotalBottles;
            $overallReturnedBottles += $driverReturnedTotalBottles;
        }

        // Sort: Active drivers first, then by sales amount descending
        usort($driverReports, function ($a, $b) {
            if ($a['is_active'] !== $b['is_active']) {
                return $b['is_active'] <=> $a['is_active'];
            }
            return $b['sales_amount'] <=> $a['sales_amount'];
        });

        $overallAssignedText = $this->bottlesToCaseText($overallAssignedBottles);
        $overallSoldText = $this->bottlesToCaseText($overallSoldBottles);
        $overallReturnedText = $this->bottlesToCaseText($overallReturnedBottles);

        return view('warehouse.daily_sales_report.index', compact([
            'reportDate',
            'warehouseDrivers',
            'selectedDriverId',
            'driverReports',
            'overallSalesAmount',
            'overallTotalInvoices',
            'overallAssignedText',
            'overallSoldText',
            'overallReturnedText',
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
            ->get()
            ->keyBy('product_id');

        $allProductIds = $assignedRows->keys()
            ->merge($soldRows->keys())
            ->merge($manualReturns->keys())
            ->unique();

        $items = [];
        $totalAssignedCases = 0;
        $totalAssignedBottles = 0;
        $totalSoldCases = 0;
        $totalSoldBottles = 0;
        $totalReturnedCases = 0;
        $totalReturnedBottles = 0;
        $totalSalesAmount = 0;

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

            // Sold
            $sRow = $soldRows->get($pid);
            $sBottles = $sRow ? (int)$sRow->sold_bottles : 0;
            $sCases = intdiv($sBottles, $bpc);
            $sLoose = $sBottles % $bpc;

            // Returned
            if ($manualReturns->has($pid)) {
                $m = $manualReturns->get($pid);
                $rCases = (int)$m->cases;
                $rLoose = (int)$m->bottles;
            } else {
                $retTotalB = max(0, $totalAssignedB - $sBottles);
                $rCases = intdiv($retTotalB, $bpc);
                $rLoose = $retTotalB % $bpc;
            }

            $itemRevenue = $sBottles * $bottlePrice;
            $totalSalesAmount += $itemRevenue;

            $totalAssignedCases += $aCases;
            $totalAssignedBottles += $aLoose;
            $totalSoldCases += $sCases;
            $totalSoldBottles += $sLoose;
            $totalReturnedCases += $rCases;
            $totalReturnedBottles += $rLoose;

            $items[] = [
                'product_id' => $p->id,
                'product_name' => $p->name,
                'bottles_per_case' => $bpc,
                'assigned_cases' => $aCases,
                'assigned_bottles' => $aLoose,
                'assigned_text' => $this->formatStockText($aCases, $aLoose),
                'sold_cases' => $sCases,
                'sold_bottles' => $sLoose,
                'sold_text' => $this->formatStockText($sCases, $sLoose),
                'returned_cases' => $rCases,
                'returned_bottles' => $rLoose,
                'returned_text' => $this->formatStockText($rCases, $rLoose),
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
                'sold_text' => $this->formatStockText($totalSoldCases, $totalSoldBottles),
                'returned_text' => $this->formatStockText($totalReturnedCases, $totalReturnedBottles),
                'sales_amount' => '₹ ' . number_format($totalSalesAmount, 2),
            ]
        ]);
    }

    public function storeReturn(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:admins,id',
            'return_date' => 'required|date',
            'returns' => 'required|array|min:1',
            'returns.*.product_id' => 'required|exists:products,id',
            'returns.*.cases' => 'nullable|integer|min:0',
            'returns.*.bottles' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $warehouse_id = Auth::guard('backend')->user()->id;
        $driver_id = (int)$request->driver_id;
        $return_date = $request->return_date;
        $restock = $request->boolean('restock_warehouse');

        // Security check: verify driver strictly belongs to this warehouse
        $isDriverAssigned = DriverWarehouse::where('warehouse_id', $warehouse_id)
            ->where('driver_id', $driver_id)
            ->exists();
        if (!$isDriverAssigned) {
            return redirect()->back()->with('error_msg', 'Selected driver is not assigned to your warehouse.');
        }

        DB::beginTransaction();
        try {
            // Delete old returns for this driver on this date
            DriverReturn::where('warehouse_id', $warehouse_id)
                ->where('driver_id', $driver_id)
                ->whereDate('return_date', $return_date)
                ->delete();

            foreach ($request->returns as $retItem) {
                $pid = (int)$retItem['product_id'];
                $c = (int)($retItem['cases'] ?? 0);
                $b = (int)($retItem['bottles'] ?? 0);

                if ($c > 0 || $b > 0) {
                    DriverReturn::create([
                        'warehouse_id' => $warehouse_id,
                        'driver_id' => $driver_id,
                        'return_date' => $return_date,
                        'product_id' => $pid,
                        'cases' => $c,
                        'bottles' => $b,
                        'notes' => $request->notes,
                    ]);

                    // Optionally restock returned goods back into warehouse stock
                    if ($restock) {
                        $this->restockWarehouse($warehouse_id, $pid, $c, $b);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->withSuccess('Driver returned stock recorded successfully.');
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

    private function bottlesToCaseText(int $totalBottles, int $bpc = 24): string
    {
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

    private function formatStockText(int $cases, int $bottles): string
    {
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
