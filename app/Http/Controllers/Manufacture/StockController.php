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
    public function index(Request $request)
    {
        $mfg_id = Auth::guard('backend')->user()->id;

        $totalProducts = Product::where('status', '1')->count();
        $totalFactoryStock = ManufactureStock::where('manufacture_id', $mfg_id)->sum('stock');
        $totalDispatched = ManufactureStockLog::where('manufacture_id', $mfg_id)->where('type', 'dispatch')->sum('quantity');
        $totalWarehouseStock = WarehouseStock::sum('stock');

        $products = Product::where('status', '1')->orderBy('name', 'asc')->get();
        $warehouses = Admin::where('role_id', '4')->where('status', '1')->get();

        return view('manufacture.stock.list', compact([
            'totalProducts',
            'totalFactoryStock',
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
                return '<strong>' . e($model->name) . '</strong>';
            })
            ->addColumn('per_case_quantity', function ($model) {
                return ($model->per_case_quantity ?? 0) . ' Bottles/Case';
            })
            ->addColumn('price', function ($model) {
                return '₹' . number_format((float)($model->per_case_price ?? 0), 2);
            })
            ->addColumn('stock_quantity', function ($model) use ($mfg_id) {
                $stockItem = ManufactureStock::where('manufacture_id', $mfg_id)
                    ->where('product_id', $model->id)
                    ->first();
                $stock = $stockItem ? (int)$stockItem->stock : 0;

                if ($stock <= 0) {
                    return '<span class="badge bg-danger font-weight-bold" style="font-size:13px;">0 Cases (Out of Stock)</span>';
                } elseif ($stock <= 50) {
                    return '<span class="badge bg-warning text-dark font-weight-bold" style="font-size:13px;">' . $stock . ' Cases (Low Stock)</span>';
                } else {
                    return '<span class="badge bg-success font-weight-bold" style="font-size:13px;">' . $stock . ' Cases</span>';
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

                $quickBtn = '<button type="button" class="btn btn-sm btn-primary quick-add-stock-btn" 
                                data-id="' . $model->id . '" 
                                data-name="' . e($model->name) . '" 
                                data-stock="' . $currentStock . '"
                                title="Add Stock (Production)">
                                <i class="fa fa-plus-circle me-1"></i> Add Stock
                             </button>';

                $logBtn = '<a href="' . route('manufacture-stock-logs', ['product_id' => $model->id]) . '" class="btn btn-sm btn-outline-secondary ms-1" title="View History">
                                <i class="fa fa-history"></i>
                           </a>';

                return '<div class="action-btns d-flex">' . $quickBtn . $logBtn . '</div>';
            })
            ->rawColumns(['product_name', 'stock_quantity', 'total_dispatched', 'action'])
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
            'products.*.quantity' => 'required|integer|min:1',
            'batch_no' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:500',
            'date' => 'nullable|date',
        ]);

        $mfg_id = Auth::guard('backend')->user()->id;
        $batch_no = $request->batch_no ?? ('BATCH-' . strtoupper(uniqid()));

        foreach ($request->products as $item) {
            $product_id = $item['product_id'];
            $quantity = (int)$item['quantity'];

            // Update or create manufacture stock
            $mStock = ManufactureStock::firstOrCreate(
                ['manufacture_id' => $mfg_id, 'product_id' => $product_id],
                ['stock' => 0]
            );
            $mStock->stock += $quantity;
            $mStock->save();

            // Record Log
            ManufactureStockLog::create([
                'manufacture_id' => $mfg_id,
                'product_id' => $product_id,
                'type' => 'production',
                'quantity' => $quantity,
                'batch_no' => $batch_no,
                'note' => $request->note ?? 'Production batch added',
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
            'quantity' => 'required|integer|min:1',
            'batch_no' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $mfg_id = Auth::guard('backend')->user()->id;
        $product_id = $request->product_id;
        $quantity = (int)$request->quantity;
        $batch_no = $request->batch_no ?? ('BATCH-' . strtoupper(uniqid()));

        $mStock = ManufactureStock::firstOrCreate(
            ['manufacture_id' => $mfg_id, 'product_id' => $product_id],
            ['stock' => 0]
        );
        $mStock->stock += $quantity;
        $mStock->save();

        ManufactureStockLog::create([
            'manufacture_id' => $mfg_id,
            'product_id' => $product_id,
            'type' => 'production',
            'quantity' => $quantity,
            'batch_no' => $batch_no,
            'note' => $request->note ?? 'Quick production stock added',
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Stock updated successfully! New Stock: ' . $mStock->stock . ' Cases.',
            'new_stock' => $mStock->stock
        ], 200);
    }

    /**
     * Warehouse Stock View (Distribution across all warehouses)
     */
    public function warehouseStock(Request $request)
    {
        $warehouses = Admin::where('role_id', '4')->where('status', '1')->get();
        $products = Product::where('status', '1')->orderBy('name', 'asc')->get();
        $totalWarehouseStock = WarehouseStock::sum('stock');

        return view('manufacture.stock.warehouse_stock', compact([
            'warehouses',
            'products',
            'totalWarehouseStock'
        ]));
    }

    /**
     * Datatable for Warehouse Stock View
     */
    public function getWarehouseStockDatatable(Request $request)
    {
        $query = DB::table('warehouse_stock')
            ->join('admins', 'warehouse_stock.warehouse_id', '=', 'admins.id')
            ->join('products', 'warehouse_stock.product_id', '=', 'products.id')
            ->select([
                'warehouse_stock.*',
                'admins.name as warehouse_name',
                'products.name as product_name',
                'products.per_case_quantity as bottles_per_case'
            ]);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_stock.warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('product_id')) {
            $query->where('warehouse_stock.product_id', $request->product_id);
        }

        $query->orderBy('admins.name', 'asc')->orderBy('products.name', 'asc');

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('warehouse_name', function ($model) {
                return '<i class="fa fa-warehouse text-primary me-1"></i> <strong>' . e($model->warehouse_name) . '</strong>';
            })
            ->addColumn('product_name', function ($model) {
                return '<strong>' . e($model->product_name) . '</strong>';
            })
            ->addColumn('bottles_per_case', function ($model) {
                return ($model->bottles_per_case ?? 0) . ' Bottles/Case';
            })
            ->addColumn('stock', function ($model) {
                $stock = (int)$model->stock;
                if ($stock <= 0) {
                    return '<span class="badge bg-danger" style="font-size:13px;">0 Cases</span>';
                } elseif ($stock <= 50) {
                    return '<span class="badge bg-warning text-dark" style="font-size:13px;">' . $stock . ' Cases (Low)</span>';
                } else {
                    return '<span class="badge bg-success" style="font-size:13px;">' . $stock . ' Cases</span>';
                }
            })
            ->addColumn('updated_date', function ($model) {
                return date('d M, Y h:i A', strtotime($model->updated_at));
            })
            ->rawColumns(['warehouse_name', 'product_name', 'stock'])
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
                return date('d M, Y h:i A', strtotime($model->created_at));
            })
            ->addColumn('product_name', function ($model) {
                return '<strong>' . e($model->product_name) . '</strong>';
            })
            ->addColumn('type', function ($model) {
                if ($model->type == 'production') {
                    return '<span class="badge bg-success"><i class="fa fa-arrow-down me-1"></i> Stock In (Production)</span>';
                } elseif ($model->type == 'dispatch') {
                    return '<span class="badge bg-info text-dark"><i class="fa fa-arrow-up me-1"></i> Stock Out (Dispatched)</span>';
                } else {
                    return '<span class="badge bg-secondary"><i class="fa fa-sync me-1"></i> Adjustment</span>';
                }
            })
            ->addColumn('quantity', function ($model) {
                $prefix = ($model->type == 'production') ? '+' : (($model->type == 'dispatch') ? '-' : '');
                $color = ($model->type == 'production') ? 'text-success' : (($model->type == 'dispatch') ? 'text-danger' : 'text-primary');
                return '<strong class="' . $color . '" style="font-size:14px;">' . $prefix . $model->quantity . ' Cases</strong>';
            })
            ->addColumn('destination', function ($model) {
                if (!empty($model->warehouse_name)) {
                    return '<i class="fa fa-warehouse text-primary me-1"></i> ' . e($model->warehouse_name);
                }
                return '<span class="text-muted">Factory / In-House</span>';
            })
            ->addColumn('batch_no', function ($model) {
                return $model->batch_no ? '<span class="badge bg-light text-dark border">' . e($model->batch_no) . '</span>' : '-';
            })
            ->addColumn('note', function ($model) {
                return e($model->note ?? '-');
            })
            ->rawColumns(['product_name', 'type', 'quantity', 'destination', 'batch_no'])
            ->make(true);
    }
}

