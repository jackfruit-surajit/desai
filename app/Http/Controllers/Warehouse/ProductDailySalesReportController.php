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
use App\Models\DriverReturn;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductDailySalesReportController extends Controller
{
    /**
     * Display product-wise daily sales report across all drivers under this warehouse.
     */
    public function index(Request $request)
    {
        $warehouse_id = Auth::guard('backend')->user()->id;
        $reportDate = $request->filled('date') ? $request->date : date('Y-m-d');
        $selectedProductId = $request->filled('product_id') ? (int)$request->product_id : null;
        $viewMode = $request->get('view_mode', 'active'); // 'active' (default) or 'all'

        // 1. Fetch drivers strictly assigned to this warehouse ONLY
        $warehouseDrivers = DriverWarehouse::join('admins', 'driver_warehouse.driver_id', '=', 'admins.id')
            ->where('driver_warehouse.warehouse_id', $warehouse_id)
            ->where('admins.is_hidden', 0)
            ->select(['admins.id', 'admins.name'])
            ->orderBy('admins.name', 'asc')
            ->get();

        $driverIds = $warehouseDrivers->pluck('id')->toArray();

        // 2. Fetch catalog products for filter dropdown & report
        $allCatalogProducts = Product::where('status', '1')
            ->orderBy('name', 'asc')
            ->get();

        $productsToQuery = $selectedProductId
            ? $allCatalogProducts->where('id', $selectedProductId)
            : $allCatalogProducts;

        // Current warehouse stock for products
        $warehouseStocks = WarehouseStock::where('warehouse_id', $warehouse_id)
            ->get()
            ->keyBy('product_id');

        $assignedRows = collect();
        $soldRows = collect();
        $manualReturns = collect();

        if (!empty($driverIds)) {
            // 3. Driver assignments on this date (grouped by product_id & driver_id)
            $assignedRows = DB::table('driver_products')
                ->join('driver_product_details', 'driver_products.id', '=', 'driver_product_details.driver_products_id')
                ->where('driver_products.warehouse_id', $warehouse_id)
                ->whereIn('driver_products.driver_id', $driverIds)
                ->where(function ($q) use ($reportDate) {
                    $q->whereDate('driver_products.assign_date', $reportDate)
                      ->orWhere(function ($sub) use ($reportDate) {
                          $sub->whereNull('driver_products.assign_date')
                              ->whereDate('driver_products.created_at', $reportDate);
                      });
                })
                ->select([
                    'driver_product_details.product_id',
                    'driver_products.driver_id',
                    DB::raw('SUM(driver_product_details.quantity) as cases'),
                    DB::raw('SUM(driver_product_details.bottles) as bottles')
                ])
                ->groupBy('driver_product_details.product_id', 'driver_products.driver_id')
                ->get();

            // 4. Sales by drivers on this date (grouped by product_id & driver_id)
            $soldRows = DB::table('invoice')
                ->join('invoice_data', 'invoice.id', '=', 'invoice_data.invoice_id')
                ->whereIn('invoice.driver_id', $driverIds)
                ->whereDate('invoice.created_at', $reportDate)
                ->select([
                    'invoice_data.product_id',
                    'invoice.driver_id',
                    DB::raw('SUM(invoice_data.quantity) as sold_bottles'),
                    DB::raw('COUNT(DISTINCT invoice.id) as invoices_count')
                ])
                ->groupBy('invoice_data.product_id', 'invoice.driver_id')
                ->get();

            // 5. Returns recorded on this date (grouped by product_id & driver_id)
            $manualReturns = DriverReturn::where('warehouse_id', $warehouse_id)
                ->whereIn('driver_id', $driverIds)
                ->whereDate('return_date', $reportDate)
                ->get();
        }

        // Group queries by product_id for fast lookup
        $assignedByProduct = $assignedRows->groupBy('product_id');
        $soldByProduct = $soldRows->groupBy('product_id');
        $returnsByProduct = $manualReturns->groupBy('product_id');

        $productReports = [];
        $overallSalesAmount = 0;
        $overallTotalAssignedBottles = 0;
        $overallTotalSoldBottles = 0;
        $overallTotalReturnedBottles = 0;
        $overallAssignedCases = 0;
        $overallAssignedLoose = 0;
        $overallSoldCases = 0;
        $overallSoldLoose = 0;
        $overallReturnedCases = 0;
        $overallReturnedLoose = 0;
        $activeProductsCount = 0;

        foreach ($productsToQuery as $product) {
            $pid = $product->id;
            $bpc = ($product->per_case_quantity > 0) ? (int)$product->per_case_quantity : 24;
            $bottlePrice = (float)($product->per_bottle_price ?? 0);
            $casePrice = (float)($product->per_case_price ?? ($bottlePrice * $bpc));

            $pAssignedItems = $assignedByProduct->get($pid, collect());
            $pSoldItems = $soldByProduct->get($pid, collect());
            $pReturnItems = $returnsByProduct->get($pid, collect());

            // Build driver breakdown for this product
            $driversInvolvedIds = $pAssignedItems->pluck('driver_id')
                ->merge($pSoldItems->pluck('driver_id'))
                ->merge($pReturnItems->pluck('driver_id'))
                ->unique();

            $driverBreakdowns = [];
            $pTotalAssignedBottles = 0;
            $pTotalSoldBottles = 0;
            $pTotalReturnedBottles = 0;
            $pTotalRevenue = 0;

            foreach ($warehouseDrivers as $driver) {
                $did = $driver->id;
                if (!$driversInvolvedIds->contains($did)) {
                    continue;
                }

                // Driver's assigned
                $dAssigned = $pAssignedItems->firstWhere('driver_id', $did);
                $dACases = $dAssigned ? (int)$dAssigned->cases : 0;
                $dABottles = $dAssigned ? (int)$dAssigned->bottles : 0;
                $dTotalAssignedB = ($dACases * $bpc) + $dABottles;

                // Driver's sold
                $dSold = $pSoldItems->firstWhere('driver_id', $did);
                $dTotalSoldB = $dSold ? (int)$dSold->sold_bottles : 0;
                $dInvoicesCount = $dSold ? (int)$dSold->invoices_count : 0;

                // Driver's returned
                $dRet = $pReturnItems->firstWhere('driver_id', $did);
                $hasManualRet = !is_null($dRet);
                if ($hasManualRet) {
                    $dRCases = (int)$dRet->cases;
                    $dRBottles = (int)$dRet->bottles;
                    $dTotalReturnedB = ($dRCases * $bpc) + $dRBottles;
                } else {
                    $dTotalReturnedB = max(0, $dTotalAssignedB - $dTotalSoldB);
                    $dRCases = intdiv($dTotalReturnedB, $bpc);
                    $dRBottles = $dTotalReturnedB % $bpc;
                }

                $dRevenue = $dTotalSoldB * $bottlePrice;

                $pTotalAssignedBottles += $dTotalAssignedB;
                $pTotalSoldBottles += $dTotalSoldB;
                $pTotalReturnedBottles += $dTotalReturnedB;
                $pTotalRevenue += $dRevenue;

                $driverBreakdowns[] = [
                    'driver_id' => $driver->id,
                    'driver_name' => $driver->name,
                    'assigned_cases' => $dACases,
                    'assigned_bottles' => $dABottles,
                    'assigned_total_bottles' => $dTotalAssignedB,
                    'assigned_text' => $this->formatStockText($dACases, $dABottles),
                    'sold_cases' => intdiv($dTotalSoldB, $bpc),
                    'sold_bottles' => $dTotalSoldB % $bpc,
                    'sold_total_bottles' => $dTotalSoldB,
                    'sold_text' => $this->formatStockText(intdiv($dTotalSoldB, $bpc), $dTotalSoldB % $bpc),
                    'returned_cases' => $dRCases,
                    'returned_bottles' => $dRBottles,
                    'returned_total_bottles' => $dTotalReturnedB,
                    'returned_text' => $this->formatStockText($dRCases, $dRBottles),
                    'has_manual_return' => $hasManualRet,
                    'revenue' => $dRevenue,
                    'invoices_count' => $dInvoicesCount,
                ];
            }

            $isActive = ($pTotalAssignedBottles > 0 || $pTotalSoldBottles > 0 || $pTotalReturnedBottles > 0);

            if ($isActive) {
                $activeProductsCount++;
            }

            // If view mode is 'active' and product has no activity, and no specific product was filtered
            if ($viewMode === 'active' && !$isActive && is_null($selectedProductId)) {
                continue;
            }

            $pAssignedCases = intdiv($pTotalAssignedBottles, $bpc);
            $pAssignedLoose = $pTotalAssignedBottles % $bpc;

            $pSoldCases = intdiv($pTotalSoldBottles, $bpc);
            $pSoldLoose = $pTotalSoldBottles % $bpc;

            $pReturnedCases = intdiv($pTotalReturnedBottles, $bpc);
            $pReturnedLoose = $pTotalReturnedBottles % $bpc;

            // Current warehouse stock for this product
            $wStock = $warehouseStocks->get($pid);
            $currStockCases = $wStock ? (int)$wStock->stock : 0;
            $currStockLoose = $wStock ? (int)$wStock->bottles : 0;
            $currentStockText = $this->formatStockText($currStockCases, $currStockLoose);

            $productReports[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'hsn_code' => $product->hsn_code ?? 'N/A',
                'bottles_per_case' => $bpc,
                'bottle_price' => $bottlePrice,
                'case_price' => $casePrice,
                'current_stock_text' => $currentStockText,
                'assigned_cases' => $pAssignedCases,
                'assigned_bottles' => $pAssignedLoose,
                'assigned_total_bottles' => $pTotalAssignedBottles,
                'assigned_text' => $this->formatStockText($pAssignedCases, $pAssignedLoose),
                'sold_cases' => $pSoldCases,
                'sold_bottles' => $pSoldLoose,
                'sold_total_bottles' => $pTotalSoldBottles,
                'sold_text' => $this->formatStockText($pSoldCases, $pSoldLoose),
                'returned_cases' => $pReturnedCases,
                'returned_bottles' => $pReturnedLoose,
                'returned_total_bottles' => $pTotalReturnedBottles,
                'returned_text' => $this->formatStockText($pReturnedCases, $pReturnedLoose),
                'sales_amount' => $pTotalRevenue,
                'active_drivers_count' => count($driverBreakdowns),
                'drivers' => $driverBreakdowns,
                'is_active' => $isActive,
            ];

            $overallSalesAmount += $pTotalRevenue;
            $overallTotalAssignedBottles += $pTotalAssignedBottles;
            $overallTotalSoldBottles += $pTotalSoldBottles;
            $overallTotalReturnedBottles += $pTotalReturnedBottles;
            $overallAssignedCases += $pAssignedCases;
            $overallAssignedLoose += $pAssignedLoose;
            $overallSoldCases += $pSoldCases;
            $overallSoldLoose += $pSoldLoose;
            $overallReturnedCases += $pReturnedCases;
            $overallReturnedLoose += $pReturnedLoose;
        }

        // Sort: Active products first, then by sales amount descending, then by name
        usort($productReports, function ($a, $b) {
            if ($a['is_active'] !== $b['is_active']) {
                return $b['is_active'] <=> $a['is_active'];
            }
            if ($a['sales_amount'] !== $b['sales_amount']) {
                return $b['sales_amount'] <=> $a['sales_amount'];
            }
            return strcmp($a['product_name'], $b['product_name']);
        });

        $overallAssignedText = $this->formatStockText($overallAssignedCases, $overallAssignedLoose);
        $overallSoldText = $this->formatStockText($overallSoldCases, $overallSoldLoose);
        $overallReturnedText = $this->formatStockText($overallReturnedCases, $overallReturnedLoose);

        return view('warehouse.product_daily_sales_report.index', compact([
            'reportDate',
            'warehouseDrivers',
            'allCatalogProducts',
            'selectedProductId',
            'viewMode',
            'productReports',
            'overallSalesAmount',
            'overallTotalAssignedBottles',
            'overallTotalSoldBottles',
            'overallTotalReturnedBottles',
            'overallAssignedCases',
            'overallAssignedLoose',
            'overallSoldCases',
            'overallSoldLoose',
            'overallReturnedCases',
            'overallReturnedLoose',
            'overallAssignedText',
            'overallSoldText',
            'overallReturnedText',
            'activeProductsCount',
        ]));
    }

    /**
     * AJAX endpoint returning driver-wise breakdown for a specific product on a specific date.
     */
    public function productDriverBreakdown(Request $request, $product_id, $date)
    {
        $warehouse_id = Auth::guard('backend')->user()->id;
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json(['status' => 404, 'message' => 'Product not found.'], 404);
        }

        $warehouseDrivers = DriverWarehouse::join('admins', 'driver_warehouse.driver_id', '=', 'admins.id')
            ->where('driver_warehouse.warehouse_id', $warehouse_id)
            ->where('admins.is_hidden', 0)
            ->select(['admins.id', 'admins.name'])
            ->orderBy('admins.name', 'asc')
            ->get();

        $driverIds = $warehouseDrivers->pluck('id')->toArray();
        $bpc = ($product->per_case_quantity > 0) ? (int)$product->per_case_quantity : 24;
        $bottlePrice = (float)($product->per_bottle_price ?? 0);

        if (empty($driverIds)) {
            return response()->json([
                'status' => 200,
                'product_name' => $product->name,
                'report_date' => date('d F, Y', strtotime($date)),
                'items' => [],
                'totals' => [
                    'assigned_text' => '0 Cases',
                    'sold_text' => '0 Cases',
                    'returned_text' => '0 Cases',
                    'sales_amount' => '₹ 0.00'
                ]
            ]);
        }

        // Assigned
        $assignedRows = DB::table('driver_products')
            ->join('driver_product_details', 'driver_products.id', '=', 'driver_product_details.driver_products_id')
            ->where('driver_products.warehouse_id', $warehouse_id)
            ->whereIn('driver_products.driver_id', $driverIds)
            ->where('driver_product_details.product_id', $product_id)
            ->where(function ($q) use ($date) {
                $q->whereDate('driver_products.assign_date', $date)
                  ->orWhere(function ($sub) use ($date) {
                      $sub->whereNull('driver_products.assign_date')
                          ->whereDate('driver_products.created_at', $date);
                  });
            })
            ->select([
                'driver_products.driver_id',
                DB::raw('SUM(driver_product_details.quantity) as cases'),
                DB::raw('SUM(driver_product_details.bottles) as bottles')
            ])
            ->groupBy('driver_products.driver_id')
            ->get()
            ->keyBy('driver_id');

        // Sold
        $soldRows = DB::table('invoice')
            ->join('invoice_data', 'invoice.id', '=', 'invoice_data.invoice_id')
            ->whereIn('invoice.driver_id', $driverIds)
            ->where('invoice_data.product_id', $product_id)
            ->whereDate('invoice.created_at', $date)
            ->select([
                'invoice.driver_id',
                DB::raw('SUM(invoice_data.quantity) as sold_bottles'),
                DB::raw('COUNT(DISTINCT invoice.id) as invoices_count')
            ])
            ->groupBy('invoice.driver_id')
            ->get()
            ->keyBy('driver_id');

        // Manual Returns
        $manualReturns = DriverReturn::where('warehouse_id', $warehouse_id)
            ->whereIn('driver_id', $driverIds)
            ->where('product_id', $product_id)
            ->whereDate('return_date', $date)
            ->get()
            ->keyBy('driver_id');

        $items = [];
        $totAssignedCases = 0;
        $totAssignedBottles = 0;
        $totSoldCases = 0;
        $totSoldBottles = 0;
        $totReturnedCases = 0;
        $totReturnedBottles = 0;
        $totSales = 0;

        foreach ($warehouseDrivers as $driver) {
            $did = $driver->id;
            $aRow = $assignedRows->get($did);
            $sRow = $soldRows->get($did);
            $rRow = $manualReturns->get($did);

            $aCases = $aRow ? (int)$aRow->cases : 0;
            $aBottles = $aRow ? (int)$aRow->bottles : 0;
            $totalAssignedB = ($aCases * $bpc) + $aBottles;

            $sBottles = $sRow ? (int)$sRow->sold_bottles : 0;
            $sCases = intdiv($sBottles, $bpc);
            $sLoose = $sBottles % $bpc;
            $invCount = $sRow ? (int)$sRow->invoices_count : 0;

            if ($rRow) {
                $rCases = (int)$rRow->cases;
                $rBottles = (int)$rRow->bottles;
                $hasManual = true;
            } else {
                $totalRetB = max(0, $totalAssignedB - $sBottles);
                $rCases = intdiv($totalRetB, $bpc);
                $rBottles = $totalRetB % $bpc;
                $hasManual = false;
            }

            $rev = $sBottles * $bottlePrice;

            if ($totalAssignedB > 0 || $sBottles > 0 || ($rCases > 0 || $rBottles > 0)) {
                $totAssignedCases += $aCases;
                $totAssignedBottles += $aBottles;
                $totSoldCases += $sCases;
                $totSoldBottles += $sLoose;
                $totReturnedCases += $rCases;
                $totReturnedBottles += $rBottles;
                $totSales += $rev;

                $items[] = [
                    'driver_id' => $driver->id,
                    'driver_name' => $driver->name,
                    'assigned_text' => $this->formatStockText($aCases, $aBottles),
                    'sold_text' => $this->formatStockText($sCases, $sLoose),
                    'returned_text' => $this->formatStockText($rCases, $rBottles),
                    'has_manual_return' => $hasManual,
                    'sales_amount' => number_format($rev, 2),
                    'invoices_count' => $invCount,
                ];
            }
        }

        return response()->json([
            'status' => 200,
            'product_name' => $product->name,
            'report_date' => date('d F, Y', strtotime($date)),
            'bottles_per_case' => $bpc,
            'unit_price' => number_format($bottlePrice, 2),
            'items' => $items,
            'totals' => [
                'assigned_text' => $this->formatStockText($totAssignedCases, $totAssignedBottles),
                'sold_text' => $this->formatStockText($totSoldCases, $totSoldBottles),
                'returned_text' => $this->formatStockText($totReturnedCases, $totReturnedBottles),
                'sales_amount' => '₹ ' . number_format($totSales, 2),
            ]
        ]);
    }

    /**
     * Export product-wise daily sales report to CSV.
     */
    public function exportCsv(Request $request)
    {
        $warehouse_id = Auth::guard('backend')->user()->id;
        $reportDate = $request->filled('date') ? $request->date : date('Y-m-d');
        $selectedProductId = $request->filled('product_id') ? (int)$request->product_id : null;

        $warehouseDrivers = DriverWarehouse::join('admins', 'driver_warehouse.driver_id', '=', 'admins.id')
            ->where('driver_warehouse.warehouse_id', $warehouse_id)
            ->where('admins.is_hidden', 0)
            ->select(['admins.id', 'admins.name'])
            ->orderBy('admins.name', 'asc')
            ->get();

        $driverIds = $warehouseDrivers->pluck('id')->toArray();

        $allCatalogProducts = Product::where('status', '1')
            ->when($selectedProductId, fn($q) => $q->where('id', $selectedProductId))
            ->orderBy('name', 'asc')
            ->get();

        $assignedRows = collect();
        $soldRows = collect();
        $manualReturns = collect();

        if (!empty($driverIds)) {
            $assignedRows = DB::table('driver_products')
                ->join('driver_product_details', 'driver_products.id', '=', 'driver_product_details.driver_products_id')
                ->where('driver_products.warehouse_id', $warehouse_id)
                ->whereIn('driver_products.driver_id', $driverIds)
                ->where(function ($q) use ($reportDate) {
                    $q->whereDate('driver_products.assign_date', $reportDate)
                      ->orWhere(function ($sub) use ($reportDate) {
                          $sub->whereNull('driver_products.assign_date')
                              ->whereDate('driver_products.created_at', $reportDate);
                      });
                })
                ->select([
                    'driver_product_details.product_id',
                    'driver_products.driver_id',
                    DB::raw('SUM(driver_product_details.quantity) as cases'),
                    DB::raw('SUM(driver_product_details.bottles) as bottles')
                ])
                ->groupBy('driver_product_details.product_id', 'driver_products.driver_id')
                ->get();

            $soldRows = DB::table('invoice')
                ->join('invoice_data', 'invoice.id', '=', 'invoice_data.invoice_id')
                ->whereIn('invoice.driver_id', $driverIds)
                ->whereDate('invoice.created_at', $reportDate)
                ->select([
                    'invoice_data.product_id',
                    'invoice.driver_id',
                    DB::raw('SUM(invoice_data.quantity) as sold_bottles')
                ])
                ->groupBy('invoice_data.product_id', 'invoice.driver_id')
                ->get();

            $manualReturns = DriverReturn::where('warehouse_id', $warehouse_id)
                ->whereIn('driver_id', $driverIds)
                ->whereDate('return_date', $reportDate)
                ->get();
        }

        $assignedByProduct = $assignedRows->groupBy('product_id');
        $soldByProduct = $soldRows->groupBy('product_id');
        $returnsByProduct = $manualReturns->groupBy('product_id');

        $fileName = 'Product_Daily_Sales_Report_' . $reportDate . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($allCatalogProducts, $assignedByProduct, $soldByProduct, $returnsByProduct, $warehouseDrivers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                '#',
                'Product Name',
                'HSN Code',
                'Bottles Per Case',
                'Assigned Cases',
                'Assigned Bottles',
                'Total Assigned Bottles',
                'Sold Cases',
                'Sold Bottles',
                'Total Sold Bottles',
                'Returned Cases',
                'Returned Bottles',
                'Total Returned Bottles',
                'Unit Bottle Price (INR)',
                'Total Sales Revenue (INR)',
                'Active Drivers Count',
            ]);

            $sl = 1;
            foreach ($allCatalogProducts as $product) {
                $pid = $product->id;
                $bpc = ($product->per_case_quantity > 0) ? (int)$product->per_case_quantity : 24;
                $bottlePrice = (float)($product->per_bottle_price ?? 0);

                $pAssigned = $assignedByProduct->get($pid, collect());
                $pSold = $soldByProduct->get($pid, collect());
                $pRet = $returnsByProduct->get($pid, collect());

                $driversInvolved = $pAssigned->pluck('driver_id')->merge($pSold->pluck('driver_id'))->merge($pRet->pluck('driver_id'))->unique();

                $pTotAssignedB = 0;
                $pTotSoldB = 0;
                $pTotRetB = 0;

                foreach ($warehouseDrivers as $driver) {
                    $did = $driver->id;
                    if (!$driversInvolved->contains($did)) continue;

                    $dAss = $pAssigned->firstWhere('driver_id', $did);
                    $dACases = $dAss ? (int)$dAss->cases : 0;
                    $dABottles = $dAss ? (int)$dAss->bottles : 0;
                    $dTotAssB = ($dACases * $bpc) + $dABottles;

                    $dSl = $pSold->firstWhere('driver_id', $did);
                    $dTotSlB = $dSl ? (int)$dSl->sold_bottles : 0;

                    $dRt = $pRet->firstWhere('driver_id', $did);
                    if ($dRt) {
                        $dTotRtB = ((int)$dRt->cases * $bpc) + (int)$dRt->bottles;
                    } else {
                        $dTotRtB = max(0, $dTotAssB - $dTotSlB);
                    }

                    $pTotAssignedB += $dTotAssB;
                    $pTotSoldB += $dTotSlB;
                    $pTotRetB += $dTotRtB;
                }

                $rev = $pTotSoldB * $bottlePrice;

                fputcsv($handle, [
                    $sl++,
                    $product->name,
                    $product->hsn_code ?? 'N/A',
                    $bpc,
                    intdiv($pTotAssignedB, $bpc),
                    $pTotAssignedB % $bpc,
                    $pTotAssignedB,
                    intdiv($pTotSoldB, $bpc),
                    $pTotSoldB % $bpc,
                    $pTotSoldB,
                    intdiv($pTotRetB, $bpc),
                    $pTotRetB % $bpc,
                    $pTotRetB,
                    number_format($bottlePrice, 2),
                    number_format($rev, 2),
                    $driversInvolved->count(),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Format cases and loose bottles text.
     */
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
