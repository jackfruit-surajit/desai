<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\InvoiceData;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoiceHistory;
use Illuminate\Support\Facades\Validator;
use Hash;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ProductApiController extends Controller
{
    public function productList(Request $request){
        $result = Product::where('status',1)->get(['id','name','per_case_price','per_bottle_price']);
        if(count($result) > 0){
            return response()->json(['status' => 200,'message' => 'Record Found.', 'data'  =>$result],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Record Not Found.', 'data'  =>[]],200);
        }
    }
    
    
    public function getInvoiceData(Request $request){
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|integer',
            'shop_id'   => 'required|integer',
            'products' =>'required|array',
            'products.product_id.*' =>'required|integer',
            
            'products.case.*' =>'nullable|integer',
            'products.bottle.*' =>'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
            $invoice = Invoice::create([
                'driver_id' => $request->driver_id,
                'shop_id' => $request->shop_id,
            ]);
            
            foreach($request->products as $product){
                
                $product_details = Product::where('id',$product['product_id'])->first();
                
                $bottle = 0;
                if($product['case']){
                    $bottle = $product_details->per_case_quantity * $product['case'];
                }
                $bottle = $bottle + $product['bottle'];
                
                
                
                InvoiceData::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $product['product_id'],
                        'quantity'   => $bottle,
                    ]);
            }
        
        if($invoice){
            $this->updateTotalPrice($invoice->id);
            return response()->json(['status' => 200,'message' => 'Invoice data added successfully.', 'invoice_id'  =>$invoice->id],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Error.. while adding invoice data.', 'data'  =>[]],200);
        }
    }
    
    private function updateTotalPrice($invoice_id){
        $invoice = Invoice::where('id',$invoice_id)->first();
        $invoice_data = InvoiceData::join('products','invoice_data.product_id','products.id')
                        ->where('invoice_data.invoice_id',$invoice_id)->get(['products.name','products.per_case_price','products.per_bottle_price','invoice_data.quantity']);
        
        $res = array();
        $total_price = 0;
        foreach($invoice_data as $val){
            $item = array(
                    'product_name' => $val->name,
                    'quantity' => $val->quantity,
                    'price' => $val->quantity * $val->per_bottle_price,
                );
                
            $res[] = $item;
            $total_price = $total_price + $item['price'];
        }
        
        Invoice::where('id',$invoice_id)->update(['total_price' => $total_price,]);
    }
    
    public function generatePdf_old(Request $request){
        
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        
        
        $invoice = Invoice::where('id',$request->invoice_id)->first();
        $invoice_data = InvoiceData::join('products','invoice_data.product_id','products.id')
                        ->where('invoice_data.invoice_id',$request->invoice_id)->get(['products.name','products.per_case_price','products.per_bottle_price','invoice_data.quantity']);
        $shop = Customer::where('id',$invoice->shop_id)->first(['id','shop_name','address','mobile_no']);
        
        $shop_name_slug = Str::slug($shop->shop_name, '-');
        
        $driver = DB::table('admins')->where('id',$invoice->driver_id)->first(['name']);
        
        $res = array();
        // $total_price = 0;
        foreach($invoice_data as $val){
            $item = array(
                    'product_name' => $val->name,
                    'quantity'     => $val->quantity,
                    'price'        => $val->quantity * $val->per_case_price,
                    'cgst'         => $val->per_case_price * ($val->cgst / 100),
                    'sgst'         => $val->per_case_price * ($val->sgst / 100),
                );
                
            $res[] = $item;
            // $total_price = $total_price + $item['price'];
        }
        
        $data = [
            'products' => $res,
            'shop' => $shop,
            'invoice' => $invoice,
            'driver' => $driver,
            'date' => now()->format('d-m-Y'),
        ];
    
        $pdf = Pdf::loadView('pdf.invoice', $data);

        $fileName = time().'-'.$shop_name_slug . '_' . date('d-m-Y') . '.pdf';
    
        $folder = public_path('invoice');
    
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $pdf->save($folder . '/' . $fileName);

        $pdfUrl = asset('public/invoice/' . $fileName);
        
        InvoiceHistory::create(['driver_id' => $invoice->driver_id, 'file_name' => $fileName, 'path' =>$pdfUrl]);

        return response()->json([
            'status' => true,
            'message' => 'PDF generated successfully',
            'file_name' => $fileName,
            'url' => $pdfUrl,
        ]);
    }
    

    
    public function invoiceList(Request $request){
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|integer',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        $invoices = InvoiceHistory::where('driver_id',$request->driver_id)->whereDate('created_at',$request->date)->get(['file_name','path']);
        
        if(count($invoices) > 0){
            return response()->json(['status' => 200,'message' => 'Record Found.', 'data'  =>$invoices],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Record Not Found.', 'data'  =>[]],200);
        }
    }
    
    private function numberToWords($number){
        
        $formatter = new \NumberFormatter(
            'en_IN',
            \NumberFormatter::SPELLOUT
        );
    
        return ucwords($formatter->format($number));
    }
    
    public function generatePdf(Request $request){
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|integer|exists:invoice,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status'  => 422, 'message' => $validator->errors(), 'data'    => []], 422);
        }
    
        $invoice = Invoice::find($request->invoice_id);
    
        if (!$invoice) {
            return response()->json([ 'status'  => false, 'message' => 'Invoice not found', 'data'    => []], 404);
        }
    
        /*
        |--------------------------------------------------------------------------
        | Invoice Products
        |--------------------------------------------------------------------------
        */
        $invoice_data = InvoiceData::join('products', 'invoice_data.product_id', '=','products.id')
            ->where('invoice_data.invoice_id', $request->invoice_id)
            ->get([
                'invoice_data.product_id',
                'invoice_data.quantity',
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
        $shop = Customer::where('id', $invoice->shop_id)->first(['id','shop_name','address','mobile_no','gst_no','pan_no','state','city','pin_code']);
    
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
    
        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */
        $pdf = Pdf::loadView('pdf.invoice', $data);
    
        /*
        |--------------------------------------------------------------------------
        | Save PDF
        |--------------------------------------------------------------------------
        */
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
        
    
        /*
        |--------------------------------------------------------------------------
        | Invoice History
        |--------------------------------------------------------------------------
        */
        InvoiceHistory::create([
            'invoice_id' =>$request->invoice_id,
            'driver_id' => $invoice->driver_id,
            'file_name' => $fileName,
            'path'      => $pdfUrl,
            'invoice_grand_total' => $grandTotal,
            'shop_id' =>$invoice->shop_id,
        ]);
    
        return response()->json(['status'    => true, 'message'   => 'PDF generated successfully', 'file_name' => $fileName, 'url' => $pdfUrl,]);
    }
    
    
}