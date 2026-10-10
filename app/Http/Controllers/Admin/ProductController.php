<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Area;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use URL;

class ProductController extends Controller
{
    
    public function index(Request $request){
        return view('admin.product.list');
    }
    
    public function create(Request $request){
        return view('admin.product.add');
    }
    
    public function store(Request $request){

        $request->validate([
            'product_name'     => 'required|string|unique:products,name',
            'per_case_price'   => 'required|numeric',
            'per_bottle_price' => 'required|numeric',
            'per_case_quantity' => 'required|numeric',
            'cgst' => 'required|numeric',
            'sgst' => 'required|numeric',
            'status'           => 'required',
            'hsn_code' => 'nullable|string'
        ]);

        $insert = Product::create([
                'name' => $request->product_name,
                'per_case_price' => $request->per_case_price,
                'per_bottle_price' => $request->per_bottle_price,
                'status' => $request->status,
                'per_case_quantity' => $request->per_case_quantity,
                'cgst' => $request->cgst,
                'sgst' => $request->sgst,
                'hsn_code' => $request->hsn_code,
            ]);

        if($insert){
            return redirect()->route('products')->withSuccess('Product added successfully.');
        }else{
           return redirect()->back()->withErrors('Error!! while adding product!!!'); 
        }

    }
    
    public function getProductDatatable(Request $request) {
        
        $data = DB::table('products')->orderBy('id','desc')->get();
        return Datatables::of($data)
            ->addIndexColumn()

            ->editColumn('status', function ($model) {                
                if ($model->status == '0') {
                    $status = '';
                } else if ($model->status == '1') {
                    $status = 'checked';
                } else if ($model->status == '2') {
                    $status = '';
                }
                $status = '<div class="container">
                                <div class="row text-center">
                                    <div class="col-md-12 offset-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                            type="checkbox" 
                                            role="switch" 
                                            id="flexSwitchCheckChecked_'.$model->id.'" '.$status.' 
                                            data-href="' . Route("product-status-change", ['id' => base64_encode($model->id)]) . '"
                                            onchange="changeStatusStaff(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    
                return $status;
            })

            ->addColumn('action', function ($model) {

                $edit = $delete = '';

                $edit = '<a href="' . Route("product-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary" title="Edit Product"><i class="fa fa-edit"></i> Edit</span></a>';   
                $delete = '<a href="' . Route("product-delete", ['id' => base64_encode($model->id)]) . '" ><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash" title="Delete"></i> Delete</span></a>';
             
                return
                    '<div class="action-btns">'.
                        $edit . $delete.
                    '</div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    
    public function delete($id) {
        $id = base64_decode($id);
        $model = DB::table('products')->where('id', $id)->first();
        
        if (!empty($model)) {

            if(DB::table('products')->where('id',$id)->delete()){
                return redirect()->route('products')->withSuccess('Product deleted successfully.');
            }
            else{
                return redirect()->back()->withErrors('Error!! while deleting product!!!'); 
            }
        } else {
            return redirect()->back()->withErrors('Sorry ! No product details found.'); 
        }
    }
    
    public function statusChange(Request $request,$id){
        $datareturn = [];
        $data = [];
        $input = [];
        $id = base64_decode($id);
        $input = $request->all();

        $data['status'] = $input['status'];
        $data['updated_at'] = date('Y-m-d H:i:s');

        $model = DB::table('products')->where('id',$id)->update($data);
        $datareturn['status'] = 200;
        $datareturn['msg'] = 'Product status updated successfully.';
        return response()->json($datareturn);
    }


    public function edit($id) {
        $id = base64_decode($id);
        $model = Product::where('id', $id)->first();
        if (!$model) {
            return redirect()->back()->with('error_msg', 'Invalid Link!');
        }
        $data['id'] = $id;
        $data['model'] = $model;
        return view('admin.product.edit', $data);
    }

    public function update(Request $request) {
       $request->validate([
            'product_id' => 'required|integer',
            'product_name'     => 'required|string|unique:products,name,'.$request->product_id,
            'per_case_price'   => 'required|numeric',
            'per_bottle_price' => 'required|numeric',
            'status'           => 'required',
            
            'per_case_quantity' => 'required|numeric',
            'cgst' => 'required|numeric',
            'sgst' => 'required|numeric',
            'hsn_code' => 'nullable|string'
        ]);
            
        $update = Product::where('id',$request->product_id)->update([
                'name' => $request->product_name,
                'per_case_price' => $request->per_case_price,
                'per_bottle_price' => $request->per_bottle_price,
                'status' => $request->status,
                'per_case_quantity' => $request->per_case_quantity,
                'cgst' => $request->cgst,
                'sgst' => $request->sgst,
                'hsn_code' => $request->hsn_code,
            ]);
            
        if($update){
            return redirect()->route('products')->withSuccess('Product details updated successfully.');
        }else{
           return redirect()->back()->withErrors('Error!! while updating product!!!');
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
        
        $query = DB::table('invoice_history')->join('admins','invoice_history.driver_id','admins.id')
                ->orderBy('invoice_history.id','desc')
                ->select('invoice_history.*','admins.name as driver_name');

        if ($request->has('from_date') && !empty($request->from_date)) {
            $query->whereDate('invoice_history.created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date') && !empty($request->to_date)) {
            $query->whereDate('invoice_history.created_at', '<=', $request->to_date);
        }

        $data = $query->get();

        return Datatables::of($data)
            ->addIndexColumn()
            
            ->editColumn('created_date', function ($model) {                
                return date('d/m/Y H:i',strtotime($model->created_at));
            })

            ->addColumn('action', function ($model) {

                $edit = $delete = '';

                $edit = '<a href="' . $model->path . '" target="_blank"><span class="badge rounded-pill text-bg-primary" title="Download Invoice"><i class="fa fa-file"></i> Print</span></a>';   

                return
                    '<div class="action-btns">'.
                        $edit . $delete.
                    '</div>';
            })
            ->rawColumns(['action','created_date'])
            ->make(true);
    }
 
    
}