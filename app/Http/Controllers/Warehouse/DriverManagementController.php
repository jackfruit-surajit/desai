<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Admin;
use App\Models\Product;
use App\Models\WarehouseStock;
use App\Models\WarehouseProduct;
use App\Models\DriverProductDetails;
use App\Models\DriverProduct;
use App\Models\DriverWarehouse;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use URL;
use Auth;

class DriverManagementController extends Controller
{
    
    public function productAdd(Request $request){
        $warehouse_id = Auth::guard('backend')->user()->id;

        // Fetch drivers strictly assigned to this warehouse via driver_warehouse ONLY
        $drivers = DriverWarehouse::join('admins','driver_warehouse.driver_id','admins.id')
                    ->where('driver_warehouse.warehouse_id', $warehouse_id)
                    ->where('admins.is_hidden', 0)
                    ->orderBy('admins.name', 'asc')
                    ->get(['admins.id','admins.name']);

        // Fetch products with this warehouse's stock (cases + bottles)
        $products = DB::table('products')
            ->leftJoin('warehouse_stock', function($join) use ($warehouse_id) {
                $join->on('products.id', '=', 'warehouse_stock.product_id')
                     ->where('warehouse_stock.warehouse_id', '=', $warehouse_id);
            })
            ->where('products.status', '1')
            ->select([
                'products.id',
                'products.name',
                'products.per_case_quantity as bottles_per_case',
                DB::raw('COALESCE(warehouse_stock.stock, 0) as stock'),
                DB::raw('COALESCE(warehouse_stock.bottles, 0) as bottles')
            ])
            ->orderBy('products.name', 'asc')
            ->get();

        return view('warehouse.driver_management.product_add', compact(['drivers','products']));
    }
    
    
    public function productStore(Request $request){
        $request->validate([
            'driver_id' => 'required|exists:admins,id',
            'assign_date' => 'nullable|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.per_case_quantity' => 'nullable|integer|min:0',
            'products.*.bottles' => 'nullable|integer|min:0',
        ]);
        
        $warehouse_id = Auth::guard('backend')->user()->id;

        // Verify driver belongs strictly to this warehouse
        $isDriverAssigned = DriverWarehouse::where('warehouse_id', $warehouse_id)
            ->where('driver_id', $request->driver_id)
            ->exists();
        if (!$isDriverAssigned) {
            return redirect()->back()->withInput()->with('error_msg', 'Selected driver is not assigned to your warehouse.');
        }

        // Group requested items per product and validate quantities
        $requestedTotals = [];
        foreach ($request->products as $pItem) {
            $pId = (int)$pItem['product_id'];
            $cases = (int)($pItem['per_case_quantity'] ?? 0);
            $bottles = (int)($pItem['bottles'] ?? 0);

            if ($cases <= 0 && $bottles <= 0) {
                $pName = Product::where('id', $pId)->value('name') ?? 'Product';
                return redirect()->back()->withInput()->with('error_msg', "Please enter at least 1 Case or 1 Bottle for {$pName}.");
            }

            if (!isset($requestedTotals[$pId])) {
                $requestedTotals[$pId] = ['cases' => 0, 'bottles' => 0];
            }
            $requestedTotals[$pId]['cases'] += $cases;
            $requestedTotals[$pId]['bottles'] += $bottles;
        }

        // Verify stock sufficiency for all products
        foreach ($requestedTotals as $pId => $reqQty) {
            $wStock = WarehouseStock::where('warehouse_id', $warehouse_id)
                ->where('product_id', $pId)
                ->first();
            $availCases = $wStock ? (int)$wStock->stock : 0;
            $availBottles = $wStock ? (int)$wStock->bottles : 0;
            $bottlesPerCase = (int)Product::where('id', $pId)->value('per_case_quantity');
            $pName = Product::where('id', $pId)->value('name') ?? 'Product';

            if ($bottlesPerCase > 0) {
                $totalAvailBottles = ($availCases * $bottlesPerCase) + $availBottles;
                $totalReqBottles = ($reqQty['cases'] * $bottlesPerCase) + $reqQty['bottles'];
                if ($totalReqBottles > $totalAvailBottles) {
                    $availText = $this->formatQtyText($availCases, $availBottles);
                    $reqText = $this->formatQtyText($reqQty['cases'], $reqQty['bottles']);
                    return redirect()->back()->withInput()->with('error_msg', "Insufficient stock for {$pName}. Available: {$availText}, Requested: {$reqText}.");
                }
            } else {
                if ($reqQty['cases'] > $availCases || $reqQty['bottles'] > $availBottles) {
                    $availText = $this->formatQtyText($availCases, $availBottles);
                    $reqText = $this->formatQtyText($reqQty['cases'], $reqQty['bottles']);
                    return redirect()->back()->withInput()->with('error_msg', "Insufficient stock for {$pName}. Available: {$availText}, Requested: {$reqText}.");
                }
            }
        }

        $assignDate = $request->filled('assign_date') ? $request->assign_date : date('Y-m-d');

        $result = DriverProduct::create([
                    'driver_id' => $request->driver_id,
                    'warehouse_id' => $warehouse_id,
                    'total_products' => count($request->products),
                    'assign_date' => $assignDate,
                ]);
    
        foreach ($request->products as $product) {
            $c = (int)($product['per_case_quantity'] ?? 0);
            $b = (int)($product['bottles'] ?? 0);
            $this->updateWarehouseStock($request->driver_id, $product['product_id'], $c, $b, $result->id);
        }
    
        return redirect()->route('driver-management-report')->withSuccess('Products assigned successfully to the driver.');
    }
    
    private function updateWarehouseStock($driver_id, $product_id, $cases, $bottles, $driver_products_id){
        $warehouse_id = Auth::guard('backend')->user()->id;
        $productStock = WarehouseStock::where('warehouse_id', $warehouse_id)->where('product_id', $product_id)->first();
        if ($productStock) {
            $bottlesPerCase = (int)Product::where('id', $product_id)->value('per_case_quantity');
            if ($bottlesPerCase > 0) {
                $currentTotalBottles = ((int)$productStock->stock * $bottlesPerCase) + (int)$productStock->bottles;
                $deductTotalBottles = ($cases * $bottlesPerCase) + $bottles;
                $remainingTotalBottles = max(0, $currentTotalBottles - $deductTotalBottles);
                $newStock = intdiv($remainingTotalBottles, $bottlesPerCase);
                $newBottles = $remainingTotalBottles % $bottlesPerCase;
            } else {
                $newStock = max(0, (int)$productStock->stock - $cases);
                $newBottles = max(0, (int)$productStock->bottles - $bottles);
            }
            WarehouseStock::where('id', $productStock->id)->update([
                'stock' => $newStock,
                'bottles' => $newBottles,
            ]);

            DriverProductDetails::create([
                'driver_products_id' => $driver_products_id,
                'driver_id' => $driver_id,
                'product_id' => $product_id,
                'quantity' => $cases,
                'bottles' => $bottles,
            ]);
        }
        return true;
    }

    private function formatQtyText(int $cases, int $bottles): string
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
    
    public function driverManagementReport(Request $request){
        $warehouse_id = Auth::guard('backend')->user()->id;
        $drivers = DriverWarehouse::join('admins','driver_warehouse.driver_id','admins.id')
                    ->where('driver_warehouse.warehouse_id', $warehouse_id)
                    ->where('admins.is_hidden', 0)
                    ->orderBy('admins.name', 'asc')
                    ->get(['admins.id','admins.name']);
                    
        return view('warehouse.driver_management.daily_management_list',compact(['drivers']));
    }
    
    
    public function getDriverManagementReportDatatable(Request $request){
        $warehouse_id = Auth::guard('backend')->user()->id;
        $query = DriverProduct::join(
            'admins','driver_products.driver_id','=','admins.id'
        )->where('driver_products.warehouse_id', $warehouse_id);

        $query->leftJoin(
            DB::raw('(SELECT driver_products_id, SUM(quantity) as total_cases, SUM(bottles) as total_bottles FROM driver_product_details GROUP BY driver_products_id) as dp_qty'),
            'driver_products.id', '=', 'dp_qty.driver_products_id'
        );
    
        // Driver filter
        if ($request->filled('driver_id')) {
            $query->where('driver_products.driver_id',$request->driver_id);
        }
    
        // From date filter
        if ($request->filled('from_date')) {
            $query->where(function($q) use ($request) {
                $q->whereDate('driver_products.assign_date', '>=', $request->from_date)
                  ->orWhere(function($sub) use ($request) {
                      $sub->whereNull('driver_products.assign_date')
                          ->whereDate('driver_products.created_at', '>=', $request->from_date);
                  });
            });
        }
    
        // To date filter
        if ($request->filled('to_date')) {
            $query->where(function($q) use ($request) {
                $q->whereDate('driver_products.assign_date', '<=', $request->to_date)
                  ->orWhere(function($sub) use ($request) {
                      $sub->whereNull('driver_products.assign_date')
                          ->whereDate('driver_products.created_at', '<=', $request->to_date);
                  });
            });
        }
    
        $query->select([
            'driver_products.*',
            'admins.name as driver_name',
            DB::raw('COALESCE(dp_qty.total_cases, 0) as total_cases'),
            DB::raw('COALESCE(dp_qty.total_bottles, 0) as total_bottles'),
        ]);
    
        $query->orderBy('driver_products.id', 'desc');
    
        return Datatables::of($query)
    
            ->addIndexColumn()
    
            ->editColumn('assign_date', function ($model) {
                $d = $model->assign_date ?? $model->created_at;
                return date('d M, Y', strtotime($d));
            })

            ->editColumn('total_products', function ($model) {
                return '<span class="badge bg-light text-dark fw-bold">' . $model->total_products . ' Item' . ($model->total_products > 1 ? 's' : '') . '</span>';
            })

            ->addColumn('total_quantity', function ($model) {
                $cases = (int)$model->total_cases;
                $bottles = (int)$model->total_bottles;
                if ($cases > 0 && $bottles > 0) {
                    return '<span class="badge bg-info text-dark fw-bold" style="font-size:12px;">' . $cases . ' Cases + ' . $bottles . ' Bottles</span>';
                } elseif ($cases > 0) {
                    return '<span class="badge bg-primary text-white fw-bold" style="font-size:12px;">' . $cases . ' Cases</span>';
                } elseif ($bottles > 0) {
                    return '<span class="badge bg-warning text-dark fw-bold" style="font-size:12px;">' . $bottles . ' Bottles</span>';
                } else {
                    return '<span class="badge bg-secondary text-white fw-bold" style="font-size:12px;">0 Cases</span>';
                }
            })
    
            ->addColumn('action', function ($model) {
                $view = '<a href="' . Route("driver-product-list", ['id' => base64_encode($model->id)]) . '" class="me-1"><span class="badge rounded-pill text-bg-primary" title="View Product List"><i class="fa fa-eye"></i> View</span></a>';
                $edit = '<a href="' . Route("driver-product-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-warning text-dark" title="Edit Assignment"><i class="fa fa-pen-to-square"></i> Edit</span></a>';   
                return '<div class="action-btns d-flex align-items-center">' .
                    $view .
                    $edit .
                    '</div>';
            })
    
            ->rawColumns(['status','action','assign_date','total_products','total_quantity'])
            ->make(true);
    }
    
    
    
    public function driverProductList(Request $request,$id){
        if (!is_numeric($id)) {
            $decoded = base64_decode($id, true);
            if ($decoded !== false && is_numeric($decoded)) {
                $id = $decoded;
            }
        }

        $driver = DriverProduct::join('admins','driver_products.driver_id','=','admins.id')
            ->where('driver_products.id', $id)
            ->first(['admins.id as driver_id', 'admins.name', 'driver_products.created_at', 'driver_products.id as driver_products_id']);
        
        if (!$driver) {
            return redirect()->route('driver-management-report')->with('error_msg', 'Driver product record not found.');
        }

        $products = DriverProductDetails::join('products','driver_product_details.product_id','products.id')
            ->where('driver_product_details.driver_products_id', $id)
            ->get(['driver_product_details.*','products.name as product_name']);
            
        return view('warehouse.driver_management.product_list',compact(['driver','products']));
    }

    public function productEdit(Request $request, $id){
        if (!is_numeric($id)) {
            $decoded = base64_decode($id, true);
            if ($decoded !== false && is_numeric($decoded)) {
                $id = $decoded;
            }
        }

        $warehouse_id = Auth::guard('backend')->user()->id;

        $driverProduct = DriverProduct::where('id', $id)
            ->where('warehouse_id', $warehouse_id)
            ->first();

        if (!$driverProduct) {
            $driverProduct = DriverProduct::where('id', $id)->first();
        }

        if (!$driverProduct) {
            return redirect()->route('driver-management-report')->with('error_msg', 'Driver assignment record not found.');
        }

        // Fetch drivers strictly assigned to this warehouse via driver_warehouse ONLY
        $drivers = DriverWarehouse::join('admins','driver_warehouse.driver_id','admins.id')
                    ->where('driver_warehouse.warehouse_id', $warehouse_id)
                    ->where('admins.is_hidden', 0)
                    ->orderBy('admins.name', 'asc')
                    ->get(['admins.id','admins.name']);

        // Fetch products with this warehouse's stock (cases + bottles)
        $products = DB::table('products')
            ->leftJoin('warehouse_stock', function($join) use ($warehouse_id) {
                $join->on('products.id', '=', 'warehouse_stock.product_id')
                     ->where('warehouse_stock.warehouse_id', '=', $warehouse_id);
            })
            ->where('products.status', '1')
            ->select([
                'products.id',
                'products.name',
                'products.per_case_quantity as bottles_per_case',
                DB::raw('COALESCE(warehouse_stock.stock, 0) as stock'),
                DB::raw('COALESCE(warehouse_stock.bottles, 0) as bottles')
            ])
            ->orderBy('products.name', 'asc')
            ->get();

        $assignedProducts = DriverProductDetails::where('driver_products_id', $driverProduct->id)->get();

        return view('warehouse.driver_management.product_edit', compact(['driverProduct', 'drivers', 'products', 'assignedProducts', 'id']));
    }

    public function productUpdate(Request $request, $id){
        if (!is_numeric($id)) {
            $decoded = base64_decode($id, true);
            if ($decoded !== false && is_numeric($decoded)) {
                $id = $decoded;
            }
        }

        $warehouse_id = Auth::guard('backend')->user()->id;

        $driverProduct = DriverProduct::where('id', $id)
            ->where('warehouse_id', $warehouse_id)
            ->first();

        if (!$driverProduct) {
            $driverProduct = DriverProduct::where('id', $id)->first();
        }

        if (!$driverProduct) {
            return redirect()->route('driver-management-report')->with('error_msg', 'Driver assignment record not found.');
        }

        $request->validate([
            'driver_id' => 'required|exists:admins,id',
            'assign_date' => 'nullable|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.per_case_quantity' => 'nullable|integer|min:0',
            'products.*.bottles' => 'nullable|integer|min:0',
        ]);

        // Verify driver belongs strictly to this warehouse
        $isDriverAssigned = DriverWarehouse::where('warehouse_id', $warehouse_id)
            ->where('driver_id', $request->driver_id)
            ->exists();
        if (!$isDriverAssigned) {
            return redirect()->back()->withInput()->with('error_msg', 'Selected driver is not assigned to your warehouse.');
        }

        DB::beginTransaction();
        try {
            // 1. Revert previous stock deduction back to warehouse_stock
            $oldDetails = DriverProductDetails::where('driver_products_id', $driverProduct->id)->get();
            foreach ($oldDetails as $oldItem) {
                $this->revertWarehouseStock($oldItem->product_id, (int)$oldItem->quantity, (int)($oldItem->bottles ?? 0));
            }

            // 2. Validate requested quantities and check sufficiency against reverted warehouse stock
            $requestedTotals = [];
            foreach ($request->products as $pItem) {
                $pId = (int)$pItem['product_id'];
                $cases = (int)($pItem['per_case_quantity'] ?? 0);
                $bottles = (int)($pItem['bottles'] ?? 0);

                if ($cases <= 0 && $bottles <= 0) {
                    $pName = Product::where('id', $pId)->value('name') ?? 'Product';
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('error_msg', "Please enter at least 1 Case or 1 Bottle for {$pName}.");
                }

                if (!isset($requestedTotals[$pId])) {
                    $requestedTotals[$pId] = ['cases' => 0, 'bottles' => 0];
                }
                $requestedTotals[$pId]['cases'] += $cases;
                $requestedTotals[$pId]['bottles'] += $bottles;
            }

            // Check if sufficient stock is available in warehouse_stock
            foreach ($requestedTotals as $pId => $reqQty) {
                $wStock = WarehouseStock::where('warehouse_id', $warehouse_id)->where('product_id', $pId)->first();
                $availCases = $wStock ? (int)$wStock->stock : 0;
                $availBottles = $wStock ? (int)$wStock->bottles : 0;
                $bottlesPerCase = (int)Product::where('id', $pId)->value('per_case_quantity');
                $pName = Product::where('id', $pId)->value('name') ?? 'Product';

                if ($bottlesPerCase > 0) {
                    $totalAvail = ($availCases * $bottlesPerCase) + $availBottles;
                    $totalReq = ($reqQty['cases'] * $bottlesPerCase) + $reqQty['bottles'];
                    if ($totalReq > $totalAvail) {
                        DB::rollBack();
                        $availText = $this->formatQtyText($availCases, $availBottles);
                        $reqText = $this->formatQtyText($reqQty['cases'], $reqQty['bottles']);
                        return redirect()->back()->withInput()->with('error_msg', "Insufficient stock for {$pName}. Available: {$availText}, Requested: {$reqText}.");
                    }
                } else {
                    if ($reqQty['cases'] > $availCases || $reqQty['bottles'] > $availBottles) {
                        DB::rollBack();
                        $availText = $this->formatQtyText($availCases, $availBottles);
                        $reqText = $this->formatQtyText($reqQty['cases'], $reqQty['bottles']);
                        return redirect()->back()->withInput()->with('error_msg', "Insufficient stock for {$pName}. Available: {$availText}, Requested: {$reqText}.");
                    }
                }
            }

            // 3. Delete old details
            DriverProductDetails::where('driver_products_id', $driverProduct->id)->delete();

            // 4. Update DriverProduct header
            $assignDate = $request->filled('assign_date') ? $request->assign_date : date('Y-m-d');
            $driverProduct->update([
                'driver_id' => $request->driver_id,
                'total_products' => count($request->products),
                'assign_date' => $assignDate,
            ]);

            // 5. Deduct updated quantities from warehouse_stock and create new details
            foreach ($request->products as $product) {
                $c = (int)($product['per_case_quantity'] ?? 0);
                $b = (int)($product['bottles'] ?? 0);
                $this->updateWarehouseStock($request->driver_id, $product['product_id'], $c, $b, $driverProduct->id);
            }

            DB::commit();
            return redirect()->route('driver-management-report')->withSuccess('Driver product assignment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error_msg', 'Failed to update assignment: ' . $e->getMessage());
        }
    }

    private function revertWarehouseStock($product_id, $cases, $bottles){
        $warehouse_id = Auth::guard('backend')->user()->id;
        $productStock = WarehouseStock::where('warehouse_id', $warehouse_id)->where('product_id', $product_id)->first();
        if ($productStock) {
            $bottlesPerCase = (int)Product::where('id', $product_id)->value('per_case_quantity');
            if ($bottlesPerCase > 0) {
                $currentTotalBottles = ((int)$productStock->stock * $bottlesPerCase) + (int)$productStock->bottles;
                $addTotalBottles = ($cases * $bottlesPerCase) + $bottles;
                $newTotalBottles = $currentTotalBottles + $addTotalBottles;
                $newStock = intdiv($newTotalBottles, $bottlesPerCase);
                $newBottles = $newTotalBottles % $bottlesPerCase;
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
        return true;
    }
    
    
    //=====================================================================================================================================================//
    
    
    
    

    public function edit($id) {
        $id = base64_decode($id);
        $model = WarehouseProduct::where('id', $id)->first();
        $warehouses = Admin::where('role_id','4')->where('status','1')->get();
        $products = Product::where('status','1')->get();
        
        if (!$model) {
            return redirect()->back()->with('error_msg', 'Invalid Link!');
        }
        return view('warehouse.product.edit',compact(['model','warehouses','products','id']));
    }
    
    public function update(Request $request){
        $request->validate([
            'warehouse_products_id' => 'required|integer',
            'status' => 'required|string',
            'comment' => 'required|string',
        ]);
    

            WarehouseProduct::where('id',$request->warehouse_products_id)->update([
                'status'   => $request->status,
                'comment'  => $request->comment,
            ]);
            
            if($request->status == '1'){
                $this->WarehouseStock($request->warehouse_products_id);
            }
            
        return redirect()->route('warehouse-products')->withSuccess('Stock update successfully.');
    }
    
    private function WarehouseStock($warehouse_products_id){
        $stock_details = WarehouseProduct::where('id',$warehouse_products_id)->first();
        if(!$stock_details) return;

        if($product_stock = WarehouseStock::where('warehouse_id', $stock_details->warehouse_id)->where('product_id',$stock_details->product_id)->first()){
            $final_stock = $product_stock->stock + $stock_details->per_case_quantity;
            WarehouseStock::where('id', $product_stock->id)->update(['stock' => $final_stock]);
        }
        else{
            WarehouseStock::create([
                'warehouse_id' => $stock_details->warehouse_id,
                'product_id' => $stock_details->product_id,
                'stock' => $stock_details->per_case_quantity,
            ]);
        }
    }


    
 
 
    
}