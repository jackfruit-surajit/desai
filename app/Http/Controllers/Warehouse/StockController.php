<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Admin;
use App\Models\Product;
use App\Models\WarehouseStock;
use App\Models\WarehouseProduct;
use App\Models\WarehouseInvoiceDate;
use App\Models\WarehouseInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use DB;
use URL;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    
    public function index(Request $request){
        return view('warehouse.stock.list');
    }
    
    public function getStockDatatable(Request $request) {
        
        $data = DB::table('products')->orderBy('products.name','asc')->get(['products.name as product_name','products.id']);
        return Datatables::of($data)
            ->addIndexColumn()

            ->editColumn('stock_quantity', function ($model) { 
                $user = Auth::guard('backend')->user()->id;
                $stock_quantity = 0;
                if($stock = WarehouseStock::where('product_id',$model->id)->where('warehouse_id',$user)->first()){
                    $stock_quantity = $stock->stock;
                }
                return $stock_quantity;
            })
            ->rawColumns(['stock_quantity'])
            ->make(true);
    }

    
    //===========================================================================> Invoice Module <========================================================================//
    
    public function invoice(Request $request){
        return view('warehouse.invoice.list');
    }
    
    public function getInvoiceDatatable(Request $request) {
        
        $data = DB::table('warehouse_invoice')->where('warehouse_invoice.warehouse_id', Auth::guard('backend')->user()->id)
                ->orderBy('warehouse_invoice.id','desc')->get(['warehouse_invoice.*']);
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
    
    
    public function invoiceCreate(Request $request){
        $products = Product::where('status','1')->get();
        return view('warehouse.invoice.add',compact(['products']));
    }
    
    public function invoiceStore(Request $request){
        $request->validate([
            'shop_name' => 'required|string',
            'email'     => 'nullable|email',
            'contact_no'=> 'nullable|string',
            
            'gst_no' => 'nullable|string',
            'pan_no' => 'nullable|string',
            'state' => 'nullable|string',
            
            'products' =>'required|array',
            'products.product_id.*' =>'required|integer',
            'products.quantity.*' =>'required|integer',
            'products.unit.*' =>'required|string',
        ]);
        
        
            $invoice = WarehouseInvoice::create([
                'warehouse_id'=> Auth::guard('backend')->user()->id,
                'shop_name'  => $request->shop_name,
                'contact_no' => $request->contact_no,
                'email'      => $request->email,
                
                'gst_no'      => $request->gst_no,
                'pan_no'      => $request->pan_no,
                'state'      => $request->state,
            ]);
            
            foreach($request->products as $product){
                
                $product_details = Product::where('id',$product['product_id'])->first();
                
                if($product['unit'] == 'case'){
                   $quantity = $product_details->per_case_quantity * $product['quantity'];
                }else{
                    $quantity = $product['quantity'];
                }
                
                WarehouseInvoiceDate::create([
                    'warehouse_invoice_id' => $invoice->id,
                    'product_id'           => $product['product_id'],
                    'quantity'             => $quantity,
                    'unit'                 => 'bottle',
                ]);
            }
        
        if($invoice){
            $this->generatePdf($invoice->id);
            return redirect()->route('warehouse-invoice')->withSuccess('Products added successfully.');
        }else{
            return redirect()->back()->withErrors('Sorry ! No customer details found.'); 
        }
    }
    
    private function numberToWords($number){
        
        $formatter = new \NumberFormatter(
            'en_IN',
            \NumberFormatter::SPELLOUT
        );
    
        return ucwords($formatter->format($number));
    }
    
    public function generatePdf($invoice_id){
    
        $invoice = WarehouseInvoice::find($invoice_id);
    
        if (!$invoice) {
            return response()->json([ 'status'  => false, 'message' => 'Invoice not found', 'data'    => []], 404);
        }
    
        /*
        |--------------------------------------------------------------------------
        | Invoice Products
        |--------------------------------------------------------------------------
        */
        $invoice_data = WarehouseInvoiceDate::join('products', 'warehouse_invoice_data.product_id', '=','products.id')
            ->where('warehouse_invoice_data.warehouse_invoice_id', $invoice_id)
            ->get([
                'warehouse_invoice_data.product_id',
                'warehouse_invoice_data.quantity',
                'warehouse_invoice_data.unit',
                'products.cgst',
                'products.sgst',
    
                'products.name',
                'products.hsn_code',
                'products.per_case_price',
                'products.per_bottle_price',
            ]);
    
        /*
        |--------------------------------------------------------------------------
        | Shop
        |--------------------------------------------------------------------------
        */
        $shop = WarehouseInvoice::where('id', $invoice->id)->first(['id','shop_name','contact_no','email','address','gst_no','pan_no','state',]);   //'mobile_no','city','pin_code']);
    
        /*
        |--------------------------------------------------------------------------
        | Driver
        |--------------------------------------------------------------------------
        */
        $driver = DB::table('admins')->where('id', $invoice->driver_id)->first(['name']);
    
        /*
        |--------------------------------------------------------------------------
        | Prepare Products + Calculate Totals
        |--------------------------------------------------------------------------
        */
        $products = [];
    
        $subtotal = 0;
        $totalCgst = 0;
        $totalSgst = 0;
        $totalQuantity = 0;
    
        foreach ($invoice_data as $val) {
    
            $quantity = (float) $val->quantity;
            $rate = (float) $val->per_bottle_price;
    
            // Item taxable amount
            $amount = $quantity * $rate;
    
            // Tax
            $cgstRate = (float) ($val->cgst ?? 0);
            $sgstRate = (float) ($val->sgst ?? 0);
    
            $cgstAmount = $amount * ($cgstRate / 100);
            $sgstAmount = $amount * ($sgstRate / 100);
    
            $subtotal += $amount;
            $totalCgst += $cgstAmount;
            $totalSgst += $sgstAmount;
            $totalQuantity += $quantity;
    
            $products[] = [
                'product_name' => $val->name,
                'hsn_code'     => $val->hsn_code,
                'quantity'     => $quantity,
                'rate'         => $rate,
                'per'          => 'case',
                'amount'       => $amount,
    
                'cgst_rate'    => $cgstRate,
                'cgst_amount'  => $cgstAmount,
    
                'sgst_rate'    => $sgstRate,
                'sgst_amount'  => $sgstAmount,
            ];
        }
    
        /*
        |--------------------------------------------------------------------------
        | Grand Total
        |--------------------------------------------------------------------------
        */
        $grandTotal = $subtotal + $totalCgst + $totalSgst;
    
        // Round invoice total
        $roundOff = round($grandTotal) - $grandTotal;
    
        $grandTotal = round($grandTotal);
    
        /*
        |--------------------------------------------------------------------------
        | Amount In Words
        |--------------------------------------------------------------------------
        */
        $amountInWords = $this->numberToWords($grandTotal);
    
        /*
        |--------------------------------------------------------------------------
        | HSN Summary
        |--------------------------------------------------------------------------
        */
        $hsnSummary = [];
    
        foreach ($products as $product) {
    
            $hsn = $product['hsn_code'];
    
            if (!isset($hsnSummary[$hsn])) {
                $hsnSummary[$hsn] = [
                    'hsn_code' => $hsn,
                    'taxable_value' => 0,
                ];
            }
    
            $hsnSummary[$hsn]['taxable_value'] += $product['amount'];
        }
    
        /*
        |--------------------------------------------------------------------------
        | PDF Data
        |--------------------------------------------------------------------------
        */
        $data = [
            'products'       => $products,
            'shop'           => $shop,
            'invoice'        => $invoice,
            'driver'         => $driver,
    
            'date'           => now()->format('d-m-Y'),
    
            'subtotal'       => $subtotal,
            'total_cgst'     => $totalCgst,
            'total_sgst'     => $totalSgst,
            'round_off'      => $roundOff,
            'grand_total'    => $grandTotal,
            'total_quantity' => $totalQuantity,
    
            'amount_in_words' => $amountInWords,
    
            'hsn_summary'    => array_values($hsnSummary),
        ];
    
        // dd($data);
        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */
        $pdf = Pdf::loadView('pdf.warehouse_invoice', $data);
        
        $shop_name_slug = Str::slug($shop->shop_name ?? 'shop', '-');
    
        $fileName = time()
            . '-' . $shop_name_slug
            . '_' . now()->format('d-m-Y')
            . '.pdf';
    
        $folder = public_path('invoice');
    
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }
    
        $filePath = $folder . '/' . $fileName;
        $pdf->save($filePath);
    
        /*
        |--------------------------------------------------------------------------
        | PDF URL
        |--------------------------------------------------------------------------
        */
        $pdfUrl = asset('public/invoice/' . $fileName);
        WarehouseInvoice::where('id',$invoice_id)->update(['file_name' => $fileName, 'path' => $pdfUrl, 'grand_total' =>$grandTotal]);
        
        // return $pdf->stream('invoice-' . $invoice_id . '.pdf');
        // return $pdf->download('invoice-' . $invoice_id . '.pdf');
    }


 
    
}