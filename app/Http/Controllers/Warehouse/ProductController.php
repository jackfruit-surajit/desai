<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Admin;
use App\Models\Product;
use App\Models\WarehouseStock;
use App\Models\WarehouseProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use URL;

class ProductController extends Controller
{
    
    public function index(Request $request){
        return view('warehouse.product.list');
    }
    
    public function create(Request $request){
        $warehouses = Admin::where('role_id','4')->where('status','1')->get();
        $products = Product::where('status','1')->get();
        return view('warehouse.product.add',compact(['warehouses','products']));
    }
    
    public function store(Request $request){
        $request->validate([
            'warehouse_id' => 'required|exists:admins,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.per_case_quantity' => 'required|integer|min:1',
        ]);
    
        foreach ($request->products as $product) {
    
            WarehouseProduct::create([
                'warehouse_id' => $request->warehouse_id,
                'product_id' => $product['product_id'],
                'per_case_quantity' => $product['per_case_quantity'],
            ]);
        }
    
        return redirect()->route('manufacture-products')->withSuccess('Products assigned to warehouse successfully.');
    }
    
    public function getProductDatatable(Request $request) {
        
        $data = DB::table('warehouse_products')->join('products','warehouse_products.product_id','products.id')
                ->orderBy('warehouse_products.id','desc')->get(['warehouse_products.*','products.name as product_name']);
        return Datatables::of($data)
            ->addIndexColumn()

            ->editColumn('status', function ($model) {                
                if ($model->status == '0') {
                    $status = '<span class="badge bg-warning">Pending</span>';
                } else if ($model->status == '1') {
                    $status = '<span class="badge bg-success">Verified</span>';
                } else if ($model->status == '2') {
                    $status = '<span class="badge bg-danger">Rejected</span>';
                }
                    
                return $status;
            })
            
            ->editColumn('assign_date', function ($model) {                
                return date('d M, Y',strtotime($model->created_at));
            })

            ->addColumn('action', function ($model) {

                $edit = $delete = '';

                $edit = '<a href="' . Route("warehouse-product-details", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary" title="View Details"><i class="fa fa-eye"></i></span></a>';   
                // $delete = '<a href="' . Route("manufacture-product-delete", ['id' => base64_encode($model->id)]) . '" ><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash" title="Delete"></i></span></a>';
             
                return
                    '<div class="action-btns">'.
                        $edit . $delete.
                    '</div>';
            })
            ->rawColumns(['status', 'action','assign_date'])
            ->make(true);
    }

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


    
    //=========================================================> Invoices <========================================================//
    
    public function downloadinvoice(){
        $pdf = Pdf::loadView('pdf.final_invoice');
        // return $pdf->download('invoice.pdf');
        return $pdf->stream('invoice.pdf');
    }
    
    public function invoiceList(Request $request){
        return view('admin.invoice.list');
    }
    
    public function getInvoiceDatatable(Request $request) {
        
        $data = DB::table('invoice_history')->join('admins','invoice_history.driver_id','admins.id')
                ->orderBy('invoice_history.id','desc')->get(['invoice_history.*','admins.name as driver_name']);
        return Datatables::of($data)
            ->addIndexColumn()
            
            ->editColumn('created_date', function ($model) {                
                return date('d/m/Y H:i',strtotime($model->created_at));
            })

            ->addColumn('action', function ($model) {

                $edit = $delete = '';

                $edit = '<a href="' . $model->path . '" target="_blank"><span class="badge rounded-pill text-bg-primary" title="Download Invoice"><i class="fa fa-file"></i></span></a>';   

                return
                    '<div class="action-btns">'.
                        $edit . $delete.
                    '</div>';
            })
            ->rawColumns(['action','created_date'])
            ->make(true);
    }
 
    
}