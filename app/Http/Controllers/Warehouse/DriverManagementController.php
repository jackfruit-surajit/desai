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
        $drivers = DB::table('admins')->where('role_id','3')->get();
        $products = Product::where('status','1')->get();
        return view('warehouse.driver_management.product_add',compact(['drivers','products']));
    }
    
    
    public function productStore(Request $request){
        $request->validate([
            'driver_id' => 'required|exists:admins,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.per_case_quantity' => 'required|integer|min:1',
        ]);
        
        
        $result = DriverProduct::create([
                    'driver_id' => $request->driver_id,
                    'warehouse_id' => Auth::guard('backend')->user()->id,
                    'total_products' => count($request->products),
                ]);
    
        foreach ($request->products as $product) {
            $this->updateWarehouseStock($request->driver_id,$product['product_id'],$product['per_case_quantity'],$result->id);
        }
    
        return redirect()->back()->withSuccess('Products assigned successfully to the driver.');
    }
    
    private function updateWarehouseStock($driver_id,$product_id,$quantity,$driver_products_id){
        
        $warehouse_id = Auth::guard('backend')->user()->id;
        if($product = WarehouseStock::where('warehouse_id',$warehouse_id)->where('product_id',$product_id)->where('stock','>',$quantity)->first()){
           $latest_quantity = $product->stock - $quantity;
           WarehouseStock::where('id',$product->id)->update(['stock' => $latest_quantity]);
           
           DriverProductDetails::create([
                'driver_products_id' => $driver_products_id,
                'driver_id' => $driver_id,
                'product_id' => $product_id,
                'quantity' => $quantity,
            ]);
        }
        return true;
    }
    
    public function driverManagementReport(Request $request){
        $warehouse_id = Auth::guard('backend')->user()->id;
        $drivers = DriverWarehouse::join('admins','driver_warehouse.driver_id','admins.id')
                    ->where('driver_warehouse.warehouse_id',$warehouse_id)->get(['admins.id','admins.name']);
                    
        return view('warehouse.driver_management.daily_management_list',compact(['drivers']));
    }
    
    
    public function getDriverManagementReportDatatable(Request $request){
        
        $query = DriverProduct::join(
            'admins','driver_products.driver_id','=','admins.id'
        );
    
        // Driver filter
        if ($request->filled('driver_id')) {
            $query->where('driver_products.driver_id',$request->driver_id);
        }
    
        // From date filter
        if ($request->filled('from_date')) {
            $query->whereDate('driver_products.created_at','>=',$request->from_date);
        }
    
        // To date filter
        if ($request->filled('to_date')) {
            $query->whereDate('driver_products.created_at','<=',$request->to_date);
        }
    
        $query->select([
            'driver_products.*','admins.name as driver_name'
        ]);
    
        $query->orderBy('driver_products.id', 'desc');
    
        return Datatables::of($query)
    
            ->addIndexColumn()
    
            ->editColumn('assign_date', function ($model) {
                return date(
                    'd M, Y',
                    strtotime($model->created_at)
                );
            })
    
            ->addColumn('action', function ($model) {
                $edit = '<a href="' . Route("driver-product-list", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary" title="View Product List"><i class="fa fa-eye"></i></span></a>';   
                return '<div class="action-btns">' .
                    $edit .
                    '</div>';
            })
    
            ->rawColumns(['status','action','assign_date'])
            ->make(true);
    }
    
    
    
    public function driverProductList(Request $request,$id){
        $id = base64_decode($id);
        $driver =  DriverProduct::join('admins','driver_products.driver_id','=','admins.id')->where('driver_products.id',$id)->first(['admins.id','admins.name','driver_products.created_at']);
        
        $products = DriverProductDetails::join('products','driver_product_details.product_id','products.id')
            ->where('driver_product_details.driver_products_id',$id)->get(['driver_product_details.*','products.name as product_name']);
            
        return view('warehouse.driver_management.product_list',compact(['driver','products']));
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
        
        if($product_stock = WarehouseStock::where('product_id',$stock_details->product_id)->first()){
            
            $final_stock = $product_stock->stock + $stock_details->per_case_quantity;
            WarehouseStock::where('product_id',$stock_details->product_id)->update(['stock' => $final_stock]);
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