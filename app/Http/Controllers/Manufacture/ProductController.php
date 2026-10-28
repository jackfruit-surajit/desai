<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Admin;
use App\Models\Product;
use App\Models\WarehouseProduct;
use App\Models\ManufactureStock;
use App\Models\ManufactureStockLog;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mail\ProductAssignedWarehouseMail;

class ProductController extends Controller
{
    
    public function index(Request $request){
        return view('manufacture.product.list');
    }
    
    public function create(Request $request){
        $mfg_id = Auth::guard('backend')->user()->id;
        $warehouses = Admin::where('role_id','4')->where('status','1')->get();
        $products = Product::where('status','1')->orderBy('name', 'asc')->get();

        // Attach current factory stock for this manufacturer
        $stocks = ManufactureStock::where('manufacture_id', $mfg_id)
            ->pluck('stock', 'product_id')
            ->toArray();

        foreach ($products as $product) {
            $product->factory_stock = isset($stocks[$product->id]) ? (int)$stocks[$product->id] : 0;
        }

        return view('manufacture.product.add', compact(['warehouses','products']));
    }
    
    public function store(Request $request){
        $request->validate([
            'warehouse_id' => 'required|exists:admins,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.per_case_quantity' => 'required|integer|min:1',
        ], [
            'warehouse_id.required' => 'Please select a warehouse.',
            'products.required' => 'Please add at least one product.',
            'products.*.product_id.required' => 'Please select a product.',
            'products.*.per_case_quantity.required' => 'Please enter quantity in case.',
            'products.*.per_case_quantity.min' => 'Quantity must be at least 1 case.',
        ]);

        $mfg_id = Auth::guard('backend')->user()->id;
        $warehouse = Admin::find($request->warehouse_id);

        // Sum requested quantities per product to validate combined rows
        $requestedTotals = [];
        foreach ($request->products as $p) {
            $pId = (int)$p['product_id'];
            $qty = (int)$p['per_case_quantity'];
            $requestedTotals[$pId] = ($requestedTotals[$pId] ?? 0) + $qty;
        }

        // Validate available factory stock for each product
        $stockErrors = [];
        foreach ($requestedTotals as $pId => $totalRequestedQty) {
            $mStock = ManufactureStock::where('manufacture_id', $mfg_id)
                ->where('product_id', $pId)
                ->first();
            $availableStock = $mStock ? (int)$mStock->stock : 0;
            $productObj = Product::find($pId);
            $productName = $productObj ? $productObj->name : "Product #{$pId}";

            if ($availableStock <= 0) {
                $stockErrors[] = "{$productName}: Factory stock is out of stock (Available: 0 Cases, Requested: {$totalRequestedQty} Cases).";
            } elseif ($totalRequestedQty > $availableStock) {
                $stockErrors[] = "{$productName}: Requested quantity ({$totalRequestedQty} Cases) exceeds available factory stock ({$availableStock} Cases).";
            }
        }

        if (!empty($stockErrors)) {
            return redirect()->back()
                ->withErrors($stockErrors)
                ->withInput()
                ->with('error_msg', 'Product assignment failed: Insufficient factory stock available.');
        }

        $assignedProducts = [];

        DB::beginTransaction();
        try {
            foreach ($request->products as $product) {
                WarehouseProduct::create([
                    'warehouse_id' => $request->warehouse_id,
                    'product_id' => $product['product_id'],
                    'per_case_quantity' => $product['per_case_quantity'],
                ]);

                // Deduct from Manufacture Stock
                $mStock = ManufactureStock::where('manufacture_id', $mfg_id)
                    ->where('product_id', $product['product_id'])
                    ->first();
                if ($mStock) {
                    $mStock->stock = max(0, $mStock->stock - $product['per_case_quantity']);
                    $mStock->save();
                }

                // Log deduction in ManufactureStockLog
                ManufactureStockLog::create([
                    'manufacture_id' => $mfg_id,
                    'product_id' => $product['product_id'],
                    'type' => 'dispatch',
                    'quantity' => $product['per_case_quantity'],
                    'warehouse_id' => $request->warehouse_id,
                    'note' => 'Dispatched to Warehouse: ' . ($warehouse ? $warehouse->name : '#' . $request->warehouse_id),
                ]);

                $productDetail = Product::find($product['product_id']);
                $assignedProducts[] = [
                    'name' => $productDetail ? $productDetail->name : 'N/A',
                    'per_case_quantity' => $product['per_case_quantity'],
                ];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error assigning products to warehouse: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error_msg', 'Something went wrong while assigning products: ' . $e->getMessage());
        }

        // Send Email notification to Warehouse
        try {
            if ($warehouse && !empty($warehouse->email)) {
                $manufacturer = Auth::guard('backend')->user();
                $mailData = [
                    'warehouse_name'    => $warehouse->name,
                    'warehouse_email'   => $warehouse->email,
                    'manufacturer_name' => $manufacturer ? $manufacturer->name : 'Manufacturer',
                    'products'          => $assignedProducts,
                    'date'              => date('d M, Y h:i A'),
                    'login_url'         => route('warehouse-login'),
                ];

                Mail::to($warehouse->email)->send(new ProductAssignedWarehouseMail($mailData));
            }
        } catch (\Exception $e) {
            Log::error('Error sending product assignment email to warehouse: ' . $e->getMessage());
        }

        return redirect()->route('manufacture-products')->withSuccess('Products assigned to warehouse successfully.');
    }
    
    public function getProductDatatable(Request $request) {
        
        $data = DB::table('warehouse_products')->join('admins','warehouse_products.warehouse_id','admins.id')
                ->join('products','warehouse_products.product_id','products.id')
                ->orderBy('warehouse_products.id','desc')->get(['warehouse_products.*','admins.name as warehouse_name','products.name as product_name']);
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

                $edit = '<a href="' . Route("manufacture-product-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary" title="View Details"><i class="fa fa-eye"></i> View</span></a>';   
                $delete = '<a href="' . Route("manufacture-product-delete", ['id' => base64_encode($model->id)]) . '" ><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash" title="Delete"></i> Delete</span></a>';
             
                return
                    '<div class="action-btns">'.
                        $edit . $delete.
                    '</div>';
            })
            ->rawColumns(['status', 'action','assign_date'])
            ->make(true);
    }
    
    public function delete($id) {
        $id = base64_decode($id);
        $model = DB::table('warehouse_products')->where('id', $id)->first();
        
        if (!empty($model)) {
            // Restore manufacturer stock
            $mfg_id = Auth::guard('backend')->user()->id;
            $mStock = ManufactureStock::where('manufacture_id', $mfg_id)->where('product_id', $model->product_id)->first();
            if ($mStock) {
                $mStock->stock += $model->per_case_quantity;
                $mStock->save();

                ManufactureStockLog::create([
                    'manufacture_id' => $mfg_id,
                    'product_id' => $model->product_id,
                    'type' => 'adjustment',
                    'quantity' => $model->per_case_quantity,
                    'warehouse_id' => $model->warehouse_id,
                    'note' => 'Restored stock from deleted warehouse assignment #' . $model->id,
                ]);
            }

            if(DB::table('warehouse_products')->where('id',$id)->delete()){
                return redirect()->route('manufacture-products')->withSuccess('Product assignment deleted and stock restored successfully.');
            }
            else{
                return redirect()->back()->withErrors('Error!! while deleting product!!!'); 
            }
        } else {
            return redirect()->back()->withErrors('Sorry ! No product details found.'); 
        }
    }

    public function edit($id) {
        $id = base64_decode($id);
        $model = WarehouseProduct::where('id', $id)->first();
        $warehouses = Admin::where('role_id','4')->where('status','1')->get();
        $products = Product::where('status','1')->orderBy('name', 'asc')->get();
        
        if (!$model) {
            return redirect()->back()->with('error_msg', 'Invalid Link!');
        }

        $mfg_id = Auth::guard('backend')->user()->id;
        $stocks = ManufactureStock::where('manufacture_id', $mfg_id)
            ->pluck('stock', 'product_id')
            ->toArray();

        foreach ($products as $product) {
            $product->factory_stock = isset($stocks[$product->id]) ? (int)$stocks[$product->id] : 0;
        }

        return view('manufacture.product.edit', compact(['model','warehouses','products','id']));
    }
    
    public function update(Request $request){
        $request->validate([
            'warehouse_products_id' => 'required|integer',
            'warehouse_id' => 'required|exists:admins,id',
            'product_id' => 'required|exists:products,id',
            'per_case_quantity' => 'required|integer|min:1',
        ]);

        $mfg_id = Auth::guard('backend')->user()->id;
        $oldRecord = WarehouseProduct::find($request->warehouse_products_id);
        if (!$oldRecord) {
            return redirect()->back()->with('error_msg', 'Assigned product record not found.');
        }

        if ($oldRecord->status != '0') {
            return redirect()->back()->with('error_msg', 'Only pending product assignments can be edited.');
        }

        $mStock = ManufactureStock::where('manufacture_id', $mfg_id)
            ->where('product_id', $request->product_id)
            ->first();
        $availableStock = $mStock ? (int)$mStock->stock : 0;

        // If editing same product
        if ($oldRecord->product_id == $request->product_id) {
            $diff = $request->per_case_quantity - $oldRecord->per_case_quantity;
            if ($diff > 0 && $diff > $availableStock) {
                return redirect()->back()
                    ->withInput()
                    ->with('error_msg', "Insufficient factory stock. Additional needed: {$diff} Cases, but only {$availableStock} Cases available.");
            }
            if ($mStock) {
                $mStock->stock = max(0, $mStock->stock - $diff);
                $mStock->save();
            }
        } else {
            // Changed product
            if ($request->per_case_quantity > $availableStock) {
                return redirect()->back()
                    ->withInput()
                    ->with('error_msg', "Insufficient factory stock for selected product. Requested: {$request->per_case_quantity} Cases, Available: {$availableStock} Cases.");
            }
            // Refund old product stock
            $oldStock = ManufactureStock::where('manufacture_id', $mfg_id)
                ->where('product_id', $oldRecord->product_id)
                ->first();
            if ($oldStock) {
                $oldStock->stock += $oldRecord->per_case_quantity;
                $oldStock->save();
            }
            // Deduct new product stock
            if ($mStock) {
                $mStock->stock = max(0, $mStock->stock - $request->per_case_quantity);
                $mStock->save();
            }
        }

        WarehouseProduct::where('id', $request->warehouse_products_id)->update([
            'warehouse_id'      => $request->warehouse_id,
            'product_id'        => $request->product_id,
            'per_case_quantity' => $request->per_case_quantity,
        ]);

        return redirect()->route('manufacture-products')->withSuccess('Assigned product updated successfully.');
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