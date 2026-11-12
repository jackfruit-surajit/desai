<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Admin;
use App\Models\Product;
use App\Models\WarehouseStock;
use App\Models\WarehouseProduct;
use App\Models\ManufactureStock;
use App\Models\ManufactureStockLog;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    /**
     * Display Factory Stock Overview
     */
    /**
     * Format cases and bottles into a readable string
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

    /**
     * Normalize loose bottles into full cases when bottles >= perCaseQty
     */
    private function normalizeStock(int $cases, int $bottles, int $perCaseQty): array
    {
        if ($perCaseQty > 0 && $bottles >= $perCaseQty) {
            $cases += intdiv($bottles, $perCaseQty);
            $bottles = $bottles % $perCaseQty;
        }
        return ['cases' => $cases, 'bottles' => $bottles];
    }

    public function index(Request $request)
    {
        $mfg_id = Auth::guard('backend')->user()->id;

        $totalProducts = Product::where('status', '1')->count();
        $totalFactoryStock = ManufactureStock::where('manufacture_id', $mfg_id)->sum('stock');
        $totalFactoryBottles = ManufactureStock::where('manufacture_id', $mfg_id)->sum('bottles');
        $totalDispatched = ManufactureStockLog::where('manufacture_id', $mfg_id)->where('type', 'dispatch')->sum('quantity');
        $totalWarehouseStock = WarehouseStock::sum('stock');

        $products = Product::where('status', '1')->orderBy('name', 'asc')->get();
        $warehouses = Admin::where('role_id', '4')->where('status', '1')->get();

        return view('manufacture.stock.list', compact([
            'totalProducts',
            'totalFactoryStock',
            'totalFactoryBottles',
            'totalDispatched',
            'totalWarehouseStock',
            'products',
            'warehouses'
        ]));
    }

    /**
     * Datatable for Factory Stock
     */
    public function getStockDatatable(Request $request)
    {
        $mfg_id = Auth::guard('backend')->user()->id;

        $products = Product::where('status', '1')->orderBy('name', 'asc')->get();

        return Datatables::of($products)
            ->addIndexColumn()
            ->addColumn('product_name', function ($model) {
                return '<strong class="text-white">' . e($model->name) . '</strong>';
            })
            ->addColumn('per_case_quantity', function ($model) {
                return '<span class="text-white">' . ($model->per_case_quantity ?? 0) . ' Bottles/Case</span>';
            })
            ->addColumn('price', function ($model) {
                return '<strong class="text-white">₹' . number_format((float)($model->per_case_price ?? 0), 2) . '</strong>';
            })
            ->addColumn('stock_quantity', function ($model) use ($mfg_id) {
                $stockItem = ManufactureStock::where('manufacture_id', $mfg_id)
                    ->where('product_id', $model->id)
                    ->first();
                $stock = $stockItem ? (int)$stockItem->stock : 0;
                $bottles = $stockItem ? (int)$stockItem->bottles : 0;
                $label = $this->formatStockText($stock, $bottles);

                if ($stock <= 0 && $bottles <= 0) {
                    return '<span class="badge bg-danger font-weight-bold" style="font-size:13px;">0 Cases (Out of Stock)</span>';
                } elseif ($stock <= 50) {
                    return '<span class="badge bg-warning text-dark font-weight-bold" style="font-size:13px;">' . $label . ' (Low Stock)</span>';
                } else {
                    return '<span class="badge bg-success font-weight-bold" style="font-size:13px;">' . $label . '</span>';
                }
            })
            ->addColumn('total_dispatched', function ($model) use ($mfg_id) {
                $dispatched = ManufactureStockLog::where('manufacture_id', $mfg_id)
                    ->where('product_id', $model->id)
                    ->where('type', 'dispatch')
                    ->sum('quantity');

                return '<span class="badge bg-info text-dark" style="font-size:12px;">' . ($dispatched ?? 0) . ' Cases</span>';
            })
            ->addColumn('action', function ($model) use ($mfg_id) {
                $stockItem = ManufactureStock::where('manufacture_id', $mfg_id)
                    ->where('product_id', $model->id)
                    ->first();
                $currentStock = $stockItem ? (int)$stockItem->stock : 0;
                $currentBottles = $stockItem ? (int)$stockItem->bottles : 0;

                $quickBtn = '<button type="button" class="btn btn-sm btn-primary quick-add-stock-btn" 
                                data-id="' . $model->id . '" 
                                data-name="' . e($model->name) . '" 
                                data-stock="' . $currentStock . '"
                                data-bottles="' . $currentBottles . '"
                                data-packaging="' . ($model->per_case_quantity ?? 0) . '"
                                title="Add Stock (Production)">
                                <i class="fa fa-plus-circle me-1"></i> Add Stock
                             </button>';

                $editBtn = '<button type="button" class="btn btn-sm btn-warning text-dark edit-stock-btn ms-1" 
                                data-id="' . $model->id . '" 
                                data-name="' . e($model->name) . '" 
                                data-stock="' . $currentStock . '"
                                data-bottles="' . $currentBottles . '"
                                data-packaging="' . ($model->per_case_quantity ?? 0) . '"
                                data-price="' . number_format((float)($model->per_case_price ?? 0), 2) . '"
                                data-url="' . route('manufacture-stock-edit', ['id' => base64_encode($model->id)]) . '"
                                title="Edit Stock (Adjustment)">
                                <i class="fa fa-edit me-1"></i> Edit Stock
                             </button>';

                $logBtn = '<a href="' . route('manufacture-stock-logs', ['product_id' => $model->id]) . '" class="btn btn-sm btn-outline-secondary ms-1" title="View History">
                                <i class="fa fa-history"></i>
                           </a>';

                return '<div class="action-btns d-flex align-items-center" style="white-space: nowrap;">' . $quickBtn . $editBtn . $logBtn . '</div>';
            })
            ->rawColumns(['product_name', 'per_case_quantity', 'price', 'stock_quantity', 'total_dispatched', 'action'])
            ->make(true);
    }

    /**
     * Create Page - Add Multiple Production Stock
     */
    public function create(Request $request)
    {
        $products = Product::where('status', '1')->orderBy('name', 'asc')->get();
        return view('manufacture.stock.add', compact(['products']));
    }

    /**
     * Store Multiple Production Stock Entries
     */
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'nullable|integer|min:0',
            'products.*.bottles' => 'nullable|integer|min:0',
            'batch_no' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:500',
            'date' => 'nullable|date',
        ]);

        foreach ($request->products as $index => $item) {
            $cases = (int)($item['quantity'] ?? 0);
            $bottles = (int)($item['bottles'] ?? 0);
            if ($cases <= 0 && $bottles <= 0) {
                return redirect()->back()
                    ->withErrors(['products.' . $index . '.quantity' => 'Please enter at least 1 Case or 1 Bottle for each product.'])
                    ->withInput();
            }
        }

        $mfg_id = Auth::guard('backend')->user()->id;
        $batch_no = $request->batch_no ?? ('BATCH-' . strtoupper(uniqid()));

        foreach ($request->products as $item) {
            $product_id = $item['product_id'];
            $cases = (int)($item['quantity'] ?? 0);
            $bottles = (int)($item['bottles'] ?? 0);
            $product = Product::find($product_id);
            $perCaseQty = $product ? (int)$product->per_case_quantity : 0;

            // Update or create manufacture stock
            $mStock = ManufactureStock::firstOrCreate(
                ['manufacture_id' => $mfg_id, 'product_id' => $product_id],
                ['stock' => 0, 'bottles' => 0]
            );
            $normalized = $this->normalizeStock(
                (int)$mStock->stock + $cases,
                (int)$mStock->bottles + $bottles,
                $perCaseQty
            );
            $mStock->stock = $normalized['cases'];
            $mStock->bottles = $normalized['bottles'];
            $mStock->save();

            $addedText = $this->formatStockText($cases, $bottles);

            // Record Log
            ManufactureStockLog::create([
                'manufacture_id' => $mfg_id,
                'product_id' => $product_id,
                'type' => 'production',
                'quantity' => $cases,
                'bottles' => $bottles,
                'batch_no' => $batch_no,
                'note' => $request->note ?? ("Production batch added: {$addedText}"),
                'created_at' => $request->date ? date('Y-m-d H:i:s', strtotime($request->date . ' ' . date('H:i:s'))) : now(),
            ]);
        }

        return redirect()->route('manufacture-stock')->withSuccess('Stock successfully added to factory inventory.');
    }

    /**
     * Quick Add Stock (via Modal Ajax)
     */
    public function quickAddStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:0',
            'bottles' => 'nullable|integer|min:0',
            'batch_no' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $cases = (int)($request->quantity ?? 0);
        $bottles = (int)($request->bottles ?? 0);

        if ($cases <= 0 && $bottles <= 0) {
            return response()->json([
                'status' => 422,
                'message' => 'Please enter at least 1 Case or 1 Bottle.',
            ], 422);
        }

        $mfg_id = Auth::guard('backend')->user()->id;
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        $perCaseQty = $product ? (int)$product->per_case_quantity : 0;
        $batch_no = $request->batch_no ?? ('BATCH-' . strtoupper(uniqid()));

        $mStock = ManufactureStock::firstOrCreate(
            ['manufacture_id' => $mfg_id, 'product_id' => $product_id],
            ['stock' => 0, 'bottles' => 0]
        );
        $normalized = $this->normalizeStock(
            (int)$mStock->stock + $cases,
            (int)$mStock->bottles + $bottles,
            $perCaseQty
        );
        $mStock->stock = $normalized['cases'];
        $mStock->bottles = $normalized['bottles'];
        $mStock->save();

        $addedText = $this->formatStockText($cases, $bottles);
        $newStockText = $this->formatStockText((int)$mStock->stock, (int)$mStock->bottles);

        ManufactureStockLog::create([
            'manufacture_id' => $mfg_id,
            'product_id' => $product_id,
            'type' => 'production',
            'quantity' => $cases,
            'bottles' => $bottles,
            'batch_no' => $batch_no,
            'note' => $request->note ?? ("Quick production entry: {$addedText}"),
        ]);

        return response()->json([
            'status' => 200,
            'message' => "Stock updated successfully! Added: {$addedText}. New Stock: {$newStockText}.",
            'new_stock' => $mStock->stock,
            'new_bottles' => $mStock->bottles
        ], 200);
    }

    /**
     * Edit Page - Edit / Adjust Factory Stock for a Product
     */
    public function edit($id)
    {
        $decoded_id = is_numeric($id) ? (int)$id : (int)base64_decode($id);
        $product = Product::findOrFail($decoded_id);
        $mfg_id = Auth::guard('backend')->user()->id;

        $stockItem = ManufactureStock::where('manufacture_id', $mfg_id)
            ->where('product_id', $decoded_id)
            ->first();
        $currentStock = $stockItem ? (int)$stockItem->stock : 0;
        $currentBottles = $stockItem ? (int)$stockItem->bottles : 0;

        $totalDispatched = ManufactureStockLog::where('manufacture_id', $mfg_id)
            ->where('product_id', $decoded_id)
            ->where('type', 'dispatch')
            ->sum('quantity');

        $recentLogs = ManufactureStockLog::where('manufacture_id', $mfg_id)
            ->where('product_id', $decoded_id)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('manufacture.stock.edit', compact([
            'product',
            'currentStock',
            'currentBottles',
            'totalDispatched',
            'recentLogs',
            'id'
        ]));
    }

    /**
     * Update / Adjust Factory Stock
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'stock' => 'required|integer|min:0',
            'bottles' => 'nullable|integer|min:0',
            'batch_no' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:500',
            'date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 422,
                    'message' => $validator->errors()->first(),
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput()->with('error_msg', $validator->errors()->first());
        }

        $mfg_id = Auth::guard('backend')->user()->id;
        $product_id = (int)$request->product_id;
        $product = Product::find($product_id);
        $perCaseQty = $product ? (int)$product->per_case_quantity : 0;

        $normalized = $this->normalizeStock(
            (int)$request->stock,
            (int)($request->bottles ?? 0),
            $perCaseQty
        );
        $newStock = $normalized['cases'];
        $newBottles = $normalized['bottles'];

        $mStock = ManufactureStock::firstOrCreate(
            ['manufacture_id' => $mfg_id, 'product_id' => $product_id],
            ['stock' => 0, 'bottles' => 0]
        );

        $oldStock = (int)$mStock->stock;
        $oldBottles = (int)$mStock->bottles;
        $diffCases = $newStock - $oldStock;
        $diffBottles = $newBottles - $oldBottles;

        $mStock->stock = $newStock;
        $mStock->bottles = $newBottles;
        $mStock->save();

        $oldText = $this->formatStockText($oldStock, $oldBottles);
        $newText = $this->formatStockText($newStock, $newBottles);

        // Compose meaningful adjustment log note
        $noteText = trim((string)$request->note);
        if ($perCaseQty > 0) {
            $oldTotal = ($oldStock * $perCaseQty) + $oldBottles;
            $newTotal = ($newStock * $perCaseQty) + $newBottles;
            $totalDiff = $newTotal - $oldTotal;
        } else {
            $totalDiff = $diffCases !== 0 ? $diffCases : $diffBottles;
        }

        if ($totalDiff > 0) {
            $changeDesc = "Stock increased from {$oldText} to {$newText}";
        } elseif ($totalDiff < 0) {
            $changeDesc = "Stock reduced from {$oldText} to {$newText}";
        } else {
            $changeDesc = "Stock confirmed / updated at {$newText} (No change)";
        }

        $fullNote = $noteText !== '' ? "{$changeDesc} | Note: {$noteText}" : $changeDesc;

        ManufactureStockLog::create([
            'manufacture_id' => $mfg_id,
            'product_id' => $product_id,
            'type' => 'adjustment',
            'quantity' => abs($diffCases),
            'bottles' => abs($diffBottles),
            'batch_no' => $request->batch_no ?? null,
            'note' => $fullNote,
            'created_at' => $request->date ? date('Y-m-d H:i:s', strtotime($request->date . ' ' . date('H:i:s'))) : now(),
        ]);

        $productName = $mStock->product->name ?? 'Product';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 200,
                'message' => "Stock updated successfully for {$productName}! New Stock: {$newText}.",
                'new_stock' => $newStock,
                'new_bottles' => $newBottles,
                'diff' => $diffCases
            ], 200);
        }

        return redirect()->route('manufacture-stock')->with('success', "Stock updated successfully for {$productName}! Current Stock: {$newText}.");
    }

    /**
     * Warehouse Stock View (Distribution across all warehouses)
     */
    public function warehouseStock(Request $request)
    {
        $warehouses = Admin::where('role_id', '4')->where('status', '1')->orderBy('name', 'asc')->get();
        $products = Product::where('status', '1')->orderBy('name', 'asc')->get();
        
        // Comprehensive Overview Statistics
        $totalWarehouses = Admin::where('role_id', '4')->where('status', '1')->count();
        $totalWarehouseStock = (int)WarehouseStock::sum('stock');
        $totalPendingStock = (int)WarehouseProduct::where('status', '0')->sum('per_case_quantity');
        $totalStockValue = (float)DB::table('warehouse_stock')
            ->join('products', 'warehouse_stock.product_id', '=', 'products.id')
            ->sum(DB::raw('warehouse_stock.stock * products.per_case_price'));
        $totalSkusStocked = WarehouseStock::where('stock', '>', 0)->distinct('product_id')->count('product_id');

        return view('manufacture.stock.warehouse_stock', compact([
            'warehouses',
            'products',
            'totalWarehouses',
            'totalWarehouseStock',
            'totalPendingStock',
            'totalStockValue',
            'totalSkusStocked'
        ]));
    }

    /**
     * Datatable for Warehouse Stock View
     */
    public function getWarehouseStockDatatable(Request $request)
    {
        $whProducts = DB::table('warehouse_products')->select('warehouse_id', 'product_id')->distinct();
        $whStock = DB::table('warehouse_stock')->select('warehouse_id', 'product_id')->distinct();
        $combined = $whProducts->union($whStock);

        $query = DB::query()->fromSub($combined, 'base')
            ->join('admins', 'base.warehouse_id', '=', 'admins.id')
            ->join('products', 'base.product_id', '=', 'products.id')
            ->leftJoin('warehouse_stock', function($join) {
                $join->on('base.warehouse_id', '=', 'warehouse_stock.warehouse_id')
                     ->on('base.product_id', '=', 'warehouse_stock.product_id');
            })
            ->leftJoin(DB::raw('(SELECT warehouse_id, product_id, SUM(per_case_quantity) as pending_qty FROM warehouse_products WHERE status = "0" GROUP BY warehouse_id, product_id) as pending'), function($join) {
                $join->on('base.warehouse_id', '=', 'pending.warehouse_id')
                     ->on('base.product_id', '=', 'pending.product_id');
            })
            ->select([
                'base.warehouse_id',
                'base.product_id',
                'admins.name as warehouse_name',
                'products.name as product_name',
                'products.per_case_quantity as bottles_per_case',
                'products.per_case_price',
                DB::raw('COALESCE(warehouse_stock.stock, 0) as current_stock'),
                DB::raw('COALESCE(pending.pending_qty, 0) as pending_stock'),
                'warehouse_stock.updated_at'
            ]);

        // Filter by Warehouse
        if ($request->filled('warehouse_id')) {
            $query->where('base.warehouse_id', $request->warehouse_id);
        }

        // Filter by Product
        if ($request->filled('product_id')) {
            $query->where('base.product_id', $request->product_id);
        }

        // Filter by Stock Status
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->where(DB::raw('COALESCE(warehouse_stock.stock, 0)'), '>', 50);
            } elseif ($request->stock_status === 'low_stock') {
                $query->whereBetween(DB::raw('COALESCE(warehouse_stock.stock, 0)'), [1, 50]);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where(DB::raw('COALESCE(warehouse_stock.stock, 0)'), '<=', 0);
            } elseif ($request->stock_status === 'pending') {
                $query->where(DB::raw('COALESCE(pending.pending_qty, 0)'), '>', 0);
            }
        }

        $query->orderBy('admins.name', 'asc')->orderBy('products.name', 'asc');

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('warehouse_name', function ($model) {
                return '<div class="d-flex align-items-center">
                            <span class="me-2 text-primary"><i class="fa fa-warehouse fa-lg"></i></span>
                            <div>
                                <span class="fw-bold text-white">' . e($model->warehouse_name) . '</span>
                                <div class="small text-white-50">ID: #' . $model->warehouse_id . '</div>
                            </div>
                        </div>';
            })
            ->addColumn('product_name', function ($model) {
                return '<div>
                            <span class="fw-bold text-white">' . e($model->product_name) . '</span>
                            <div class="small text-white-50">Product ID: #' . $model->product_id . '</div>
                        </div>';
            })
            ->addColumn('bottles_per_case', function ($model) {
                return '<span class="badge bg-secondary text-white border-0 px-2 py-1">' . ($model->bottles_per_case ?? 0) . ' Bottles/Case</span>';
            })
            ->addColumn('per_case_price', function ($model) {
                return '<strong class="text-white">₹' . number_format((float)($model->per_case_price ?? 0), 2) . '</strong>';
            })
            ->addColumn('current_stock', function ($model) {
                $stock = (int)$model->current_stock;
                if ($stock <= 0) {
                    return '<span class="badge bg-danger p-2" style="font-size:12px;"><i class="fa fa-circle-xmark me-1"></i> 0 Cases (Out of Stock)</span>';
                } elseif ($stock <= 50) {
                    return '<span class="badge bg-warning text-dark p-2" style="font-size:12px;"><i class="fa fa-triangle-exclamation me-1"></i> ' . $stock . ' Cases (Low)</span>';
                } else {
                    return '<span class="badge bg-success p-2" style="font-size:12px;"><i class="fa fa-circle-check me-1"></i> ' . $stock . ' Cases</span>';
                }
            })
            ->addColumn('pending_stock', function ($model) {
                $pending = (int)$model->pending_stock;
                if ($pending > 0) {
                    return '<span class="badge bg-info text-dark p-2" style="font-size:12px;"><i class="fa fa-truck-fast me-1"></i> ' . $pending . ' Cases (In-Transit)</span>';
                }
                return '<span class="text-white-50">-</span>';
            })
            ->addColumn('total_pipeline', function ($model) {
                $total = (int)$model->current_stock + (int)$model->pending_stock;
                return '<strong class="text-white" style="font-size:14px;">' . $total . ' Cases</strong>';
            })
            ->addColumn('stock_value', function ($model) {
                $value = (float)($model->current_stock * ($model->per_case_price ?? 0));
                return '<span class="fw-bold text-white">₹' . number_format($value, 2) . '</span>';
            })
            ->addColumn('updated_date', function ($model) {
                return !empty($model->updated_at) ? '<span class="text-white">' . date('d M, Y h:i A', strtotime($model->updated_at)) . '</span>' : '<span class="text-white-50 small">Awaiting verification</span>';
            })
            ->addColumn('action', function ($model) {
                $assignUrl = route('manufacture-product-create') . '?warehouse_id=' . $model->warehouse_id . '&product_id=' . $model->product_id;
                return '<a href="' . $assignUrl . '" class="btn btn-sm btn-outline-primary" title="Assign More Stock">
                            <i class="fa fa-paper-plane me-1"></i> Assign Stock
                        </a>';
            })
            ->rawColumns(['warehouse_name', 'product_name', 'bottles_per_case', 'per_case_price', 'current_stock', 'pending_stock', 'total_pipeline', 'stock_value', 'updated_date', 'action'])
            ->make(true);
    }

    /**
     * Stock Logs / History View
     */
    public function stockLogs(Request $request)
    {
        $products = Product::where('status', '1')->orderBy('name', 'asc')->get();
        $warehouses = Admin::where('role_id', '4')->where('status', '1')->get();
        $selected_product = $request->product_id ?? '';

        return view('manufacture.stock.logs', compact(['products', 'warehouses', 'selected_product']));
    }

    /**
     * Datatable for Stock Logs
     */
    public function getStockLogsDatatable(Request $request)
    {
        $mfg_id = Auth::guard('backend')->user()->id;

        $query = DB::table('manufacture_stock_logs')
            ->join('products', 'manufacture_stock_logs.product_id', '=', 'products.id')
            ->leftJoin('admins as warehouse', 'manufacture_stock_logs.warehouse_id', '=', 'warehouse.id')
            ->where('manufacture_stock_logs.manufacture_id', $mfg_id)
            ->select([
                'manufacture_stock_logs.*',
                'products.name as product_name',
                'warehouse.name as warehouse_name'
            ]);

        if ($request->filled('product_id')) {
            $query->where('manufacture_stock_logs.product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('manufacture_stock_logs.type', $request->type);
        }

        $query->orderBy('manufacture_stock_logs.id', 'desc');

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('date', function ($model) {
                return '<span class="text-white">' . date('d M, Y h:i A', strtotime($model->created_at)) . '</span>';
            })
            ->addColumn('product_name', function ($model) {
                return '<strong class="text-white">' . e($model->product_name) . '</strong>';
            })
            ->addColumn('type', function ($model) {
                if ($model->type == 'production') {
                    return '<span class="badge bg-success"><i class="fa fa-arrow-down me-1"></i> Stock In (Production)</span>';
                } elseif ($model->type == 'dispatch') {
                    return '<span class="badge bg-info text-dark"><i class="fa fa-arrow-up me-1"></i> Stock Out (Dispatched)</span>';
                } else {
                    return '<span class="badge bg-warning text-dark"><i class="fa fa-sliders me-1"></i> Stock Adjustment</span>';
                }
            })
            ->addColumn('quantity', function ($model) {
                $cases = (int)($model->quantity ?? 0);
                $bottles = (int)($model->bottles ?? 0);
                $label = $this->formatStockText($cases, $bottles);

                if ($model->type == 'production') {
                    return '<strong class="text-success" style="font-size:14px;">+' . $label . '</strong>';
                } elseif ($model->type == 'dispatch') {
                    return '<strong class="text-danger" style="font-size:14px;">-' . $label . '</strong>';
                } elseif ($model->type == 'adjustment') {
                    $sign = '';
                    $color = 'text-warning';
                    if (str_contains($model->note, 'increased') || str_contains($model->note, '(+')) {
                        $sign = '+';
                        $color = 'text-success';
                    } elseif (str_contains($model->note, 'reduced') || str_contains($model->note, '(-')) {
                        $sign = '-';
                        $color = 'text-danger';
                    }
                    return '<strong class="' . $color . '" style="font-size:14px;">' . $sign . $label . '</strong>';
                } else {
                    return '<strong class="text-info" style="font-size:14px;">' . $label . '</strong>';
                }
            })
            ->addColumn('destination', function ($model) {
                if (!empty($model->warehouse_name)) {
                    return '<i class="fa fa-warehouse text-info me-1"></i> <span class="text-white">' . e($model->warehouse_name) . '</span>';
                }
                return '<span class="text-white-50">Factory / In-House</span>';
            })
            ->addColumn('batch_no', function ($model) {
                return $model->batch_no ? '<span class="badge bg-secondary text-white border-0">' . e($model->batch_no) . '</span>' : '<span class="text-white-50">-</span>';
            })
            ->addColumn('note', function ($model) {
                return '<span class="text-white">' . e($model->note ?? '-') . '</span>';
            })
            ->rawColumns(['date', 'product_name', 'type', 'quantity', 'destination', 'batch_no', 'note'])
            ->make(true);
    }
}

