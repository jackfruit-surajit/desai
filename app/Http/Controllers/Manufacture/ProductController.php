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

    private function adjustManufactureStock(int $mfgId, int $productId, int $deltaCases, int $deltaBottles): void
    {
        $product = Product::find($productId);
        $perCaseQty = $product ? (int)$product->per_case_quantity : 0;

        $mStock = ManufactureStock::firstOrCreate(
            ['manufacture_id' => $mfgId, 'product_id' => $productId],
            ['stock' => 0, 'bottles' => 0]
        );

        if ($perCaseQty > 0) {
            $currentTotal = ((int)$mStock->stock * $perCaseQty) + (int)$mStock->bottles;
            $deltaTotal = ($deltaCases * $perCaseQty) + $deltaBottles;
            $newTotal = max(0, $currentTotal + $deltaTotal);
            $mStock->stock = intdiv($newTotal, $perCaseQty);
            $mStock->bottles = $newTotal % $perCaseQty;
        } else {
            $mStock->stock = max(0, (int)$mStock->stock + $deltaCases);
            $mStock->bottles = max(0, (int)$mStock->bottles + $deltaBottles);
        }

        $mStock->save();
    }

    public function create(Request $request){
        $mfg_id = Auth::guard('backend')->user()->id;
        $warehouses = Admin::where('role_id','4')->where('status','1')->get();
        $products = Product::where('status','1')->orderBy('name', 'asc')->get();

        // Attach current factory stock (cases + bottles) for this manufacturer
        $stockRows = ManufactureStock::where('manufacture_id', $mfg_id)
            ->get()
            ->keyBy('product_id');

        foreach ($products as $product) {
            $row = $stockRows->get($product->id);
            $product->factory_stock = $row ? (int)$row->stock : 0;
            $product->factory_bottles = $row ? (int)$row->bottles : 0;
        }

        return view('manufacture.product.add', compact(['warehouses','products']));
    }
    
    public function store(Request $request){
        $request->validate([
            'warehouse_id' => 'required|exists:admins,id',
            'assign_date' => 'nullable|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.per_case_quantity' => 'nullable|integer|min:0',
            'products.*.bottles' => 'nullable|integer|min:0',
        ], [
            'warehouse_id.required' => 'Please select a warehouse.',
            'products.required' => 'Please add at least one product.',
            'products.*.product_id.required' => 'Please select a product.',
            'products.*.per_case_quantity.min' => 'Cases cannot be negative.',
            'products.*.bottles.min' => 'Bottles cannot be negative.',
        ]);

        foreach ($request->products as $index => $p) {
            $cases = (int)($p['per_case_quantity'] ?? 0);
            $bottles = (int)($p['bottles'] ?? 0);
            if ($cases <= 0 && $bottles <= 0) {
                return redirect()->back()
                    ->withErrors(['products.' . $index . '.per_case_quantity' => 'Please enter at least 1 Case or 1 Bottle for each product.'])
                    ->withInput()
                    ->with('error_msg', 'Please enter at least 1 Case or 1 Bottle for each product.');
            }
        }

        $mfg_id = Auth::guard('backend')->user()->id;
        $warehouse = Admin::find($request->warehouse_id);

        // Sum requested quantities per product to validate combined rows
        $requestedTotals = [];
        foreach ($request->products as $p) {
            $pId = (int)$p['product_id'];
            $cases = (int)($p['per_case_quantity'] ?? 0);
            $bottles = (int)($p['bottles'] ?? 0);
            if (!isset($requestedTotals[$pId])) {
                $requestedTotals[$pId] = ['cases' => 0, 'bottles' => 0];
            }
            $requestedTotals[$pId]['cases'] += $cases;
            $requestedTotals[$pId]['bottles'] += $bottles;
        }

        // Validate available factory stock for each product
        $stockErrors = [];
        foreach ($requestedTotals as $pId => $reqQty) {
            $mStock = ManufactureStock::where('manufacture_id', $mfg_id)
                ->where('product_id', $pId)
                ->first();
            $availCases = $mStock ? (int)$mStock->stock : 0;
            $availBottles = $mStock ? (int)$mStock->bottles : 0;
            $productObj = Product::find($pId);
            $productName = $productObj ? $productObj->name : "Product #{$pId}";
            $perCaseQty = $productObj ? (int)$productObj->per_case_quantity : 0;

            $availText = $this->formatQtyText($availCases, $availBottles);
            $reqText = $this->formatQtyText($reqQty['cases'], $reqQty['bottles']);

            if ($availCases <= 0 && $availBottles <= 0) {
                $stockErrors[] = "{$productName}: Factory stock is out of stock (Available: 0 Cases, Requested: {$reqText}).";
            } elseif ($perCaseQty > 0) {
                $totalAvailBottles = ($availCases * $perCaseQty) + $availBottles;
                $totalReqBottles = ($reqQty['cases'] * $perCaseQty) + $reqQty['bottles'];
                if ($totalReqBottles > $totalAvailBottles) {
                    $stockErrors[] = "{$productName}: Requested quantity ({$reqText}) exceeds available factory stock ({$availText}).";
                }
            } elseif ($reqQty['cases'] > $availCases || $reqQty['bottles'] > $availBottles) {
                $stockErrors[] = "{$productName}: Requested quantity ({$reqText}) exceeds available factory stock ({$availText}).";
            }
        }

        if (!empty($stockErrors)) {
            return redirect()->back()
                ->withErrors($stockErrors)
                ->withInput()
                ->with('error_msg', 'Product assignment failed: Insufficient factory stock available.');
        }

        $assignedProducts = [];
        $assignDate = $request->filled('assign_date') ? date('Y-m-d', strtotime($request->assign_date)) : date('Y-m-d');
        $assignTimestamp = $assignDate . ' ' . date('H:i:s');

        DB::beginTransaction();
        try {
            foreach ($request->products as $product) {
                $cases = (int)($product['per_case_quantity'] ?? 0);
                $bottles = (int)($product['bottles'] ?? 0);

                WarehouseProduct::create([
                    'warehouse_id' => $request->warehouse_id,
                    'product_id' => $product['product_id'],
                    'per_case_quantity' => $cases,
                    'bottles' => $bottles,
                    'assign_date' => $assignDate,
                    'created_at' => $assignTimestamp,
                ]);

                // Deduct from Manufacture Stock (cases + bottles)
                $this->adjustManufactureStock($mfg_id, (int)$product['product_id'], -$cases, -$bottles);

                // Log deduction in ManufactureStockLog
                ManufactureStockLog::create([
                    'manufacture_id' => $mfg_id,
                    'product_id' => $product['product_id'],
                    'type' => 'dispatch',
                    'quantity' => $cases,
                    'bottles' => $bottles,
                    'warehouse_id' => $request->warehouse_id,
                    'note' => 'Dispatched to Warehouse: ' . ($warehouse ? $warehouse->name : '#' . $request->warehouse_id) . ' (' . $this->formatQtyText($cases, $bottles) . ')',
                    'created_at' => $assignTimestamp,
                ]);

                $productDetail = Product::find($product['product_id']);
                $assignedProducts[] = [
                    'name' => $productDetail ? $productDetail->name : 'N/A',
                    'per_case_quantity' => $cases,
                    'bottles' => $bottles,
                    'qty_text' => $this->formatQtyText($cases, $bottles),
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
                    'date'              => date('d M, Y', strtotime($assignDate)) . ' ' . date('h:i A'),
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

            ->editColumn('warehouse_name', function ($model) {
                return '<strong class="text-white">' . e($model->warehouse_name) . '</strong>';
            })

            ->editColumn('product_name', function ($model) {
                return '<strong class="text-white">' . e($model->product_name) . '</strong>';
            })

            ->editColumn('per_case_quantity', function ($model) {
                return '<span class="text-white">' . $this->formatQtyText((int)$model->per_case_quantity, (int)($model->bottles ?? 0)) . '</span>';
            })

            ->editColumn('status', function ($model) {                
                if ($model->status == '0') {
                    $status = '<span class="badge bg-warning text-dark fw-bold">Pending</span>';
                } else if ($model->status == '1') {
                    $status = '<span class="badge bg-success fw-bold">Verified</span>';
                } else if ($model->status == '2') {
                    $status = '<span class="badge bg-danger fw-bold">Rejected</span>';
                }
                    
                return $status;
            })
            
            ->editColumn('assign_date', function ($model) {
                $dateVal = !empty($model->assign_date) ? $model->assign_date : $model->created_at;
                return '<span class="text-white">' . date('d M, Y', strtotime($dateVal)) . '</span>';
            })

            ->addColumn('accept_date', function ($model) {
                if ($model->status == '1') {
                    $acceptVal = !empty($model->accept_date) ? $model->accept_date : $model->updated_at;
                    return !empty($acceptVal) ? '<span class="text-success fw-bold">' . date('d M, Y', strtotime($acceptVal)) . '</span>' : '<span class="text-white-50">-</span>';
                }
                return '<span class="text-white-50">-</span>';
            })

            ->addColumn('action', function ($model) {

                $edit = $delete = '';

                $edit = '<a href="' . Route("manufacture-product-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary" title="View / Edit Details"><i class="fa fa-eye"></i> View</span></a>';   
                $delete = '<a href="' . Route("manufacture-product-delete", ['id' => base64_encode($model->id)]) . '" ><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash" title="Delete"></i> Delete</span></a>';
             
                return
                    '<div class="action-btns">'.
                        $edit . $delete.
                    '</div>';
            })
            ->rawColumns(['warehouse_name', 'product_name', 'per_case_quantity', 'status', 'action', 'assign_date', 'accept_date'])
            ->make(true);
    }
    
    public function delete($id) {
        $id = base64_decode($id);
        $model = DB::table('warehouse_products')->where('id', $id)->first();
        
        if (!empty($model)) {
            // Restore manufacturer stock (cases + bottles)
            $mfg_id = Auth::guard('backend')->user()->id;
            $oldCases = (int)$model->per_case_quantity;
            $oldBottles = (int)($model->bottles ?? 0);

            $this->adjustManufactureStock($mfg_id, (int)$model->product_id, $oldCases, $oldBottles);

            ManufactureStockLog::create([
                'manufacture_id' => $mfg_id,
                'product_id' => $model->product_id,
                'type' => 'adjustment',
                'quantity' => $oldCases,
                'bottles' => $oldBottles,
                'warehouse_id' => $model->warehouse_id,
                'note' => 'Restored stock from deleted warehouse assignment #' . $model->id . ' (' . $this->formatQtyText($oldCases, $oldBottles) . ')',
            ]);

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
        $stockRows = ManufactureStock::where('manufacture_id', $mfg_id)
            ->get()
            ->keyBy('product_id');

        foreach ($products as $product) {
            $row = $stockRows->get($product->id);
            $product->factory_stock = $row ? (int)$row->stock : 0;
            $product->factory_bottles = $row ? (int)$row->bottles : 0;
        }

        return view('manufacture.product.edit', compact(['model','warehouses','products','id']));
    }
    
    public function update(Request $request){
        $oldRecord = WarehouseProduct::find($request->warehouse_products_id);
        if (!$oldRecord) {
            return redirect()->back()->with('error_msg', 'Assigned product record not found.');
        }

        // Allow updating assign_date even after warehouse verification/rejection without altering stock
        if ($oldRecord->status != '0') {
            $request->validate([
                'warehouse_products_id' => 'required|integer',
                'assign_date' => 'required|date',
            ]);
            $assignDate = date('Y-m-d', strtotime($request->assign_date));
            $oldTime = $oldRecord->created_at ? date('H:i:s', strtotime($oldRecord->created_at)) : date('H:i:s');

            WarehouseProduct::where('id', $request->warehouse_products_id)->update([
                'assign_date' => $assignDate,
                'created_at'  => $assignDate . ' ' . $oldTime,
            ]);

            return redirect()->route('manufacture-products')->withSuccess('Assign date updated successfully.');
        }

        $request->validate([
            'warehouse_products_id' => 'required|integer',
            'warehouse_id' => 'required|exists:admins,id',
            'product_id' => 'required|exists:products,id',
            'per_case_quantity' => 'nullable|integer|min:0',
            'bottles' => 'nullable|integer|min:0',
            'assign_date' => 'nullable|date',
        ]);

        $newCases = (int)($request->per_case_quantity ?? 0);
        $newBottles = (int)($request->bottles ?? 0);
        if ($newCases <= 0 && $newBottles <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error_msg', 'Please enter at least 1 Case or 1 Bottle.');
        }

        $mfg_id = Auth::guard('backend')->user()->id;
        $productObj = Product::find($request->product_id);
        $perCaseQty = $productObj ? (int)$productObj->per_case_quantity : 0;

        $mStock = ManufactureStock::where('manufacture_id', $mfg_id)
            ->where('product_id', $request->product_id)
            ->first();
        $availCases = $mStock ? (int)$mStock->stock : 0;
        $availBottles = $mStock ? (int)$mStock->bottles : 0;

        $oldCases = (int)$oldRecord->per_case_quantity;
        $oldBottles = (int)($oldRecord->bottles ?? 0);
        $reqText = $this->formatQtyText($newCases, $newBottles);
        $availText = $this->formatQtyText($availCases, $availBottles);

        // If editing same product
        if ($oldRecord->product_id == $request->product_id) {
            if ($perCaseQty > 0) {
                $availTotal = ($availCases * $perCaseQty) + $availBottles + ($oldCases * $perCaseQty) + $oldBottles;
                $reqTotal = ($newCases * $perCaseQty) + $newBottles;
                if ($reqTotal > $availTotal) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error_msg', "Insufficient factory stock. Requested ({$reqText}) exceeds available factory stock.");
                }
            } else {
                if (($newCases - $oldCases) > $availCases || ($newBottles - $oldBottles) > $availBottles) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error_msg', "Insufficient factory stock. Available: {$availText}.");
                }
            }
            $this->adjustManufactureStock($mfg_id, (int)$request->product_id, $oldCases - $newCases, $oldBottles - $newBottles);
        } else {
            // Changed product
            if ($perCaseQty > 0) {
                $availTotal = ($availCases * $perCaseQty) + $availBottles;
                $reqTotal = ($newCases * $perCaseQty) + $newBottles;
                if ($reqTotal > $availTotal) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error_msg', "Insufficient factory stock for selected product. Requested: {$reqText}, Available: {$availText}.");
                }
            } elseif ($newCases > $availCases || $newBottles > $availBottles) {
                return redirect()->back()
                    ->withInput()
                    ->with('error_msg', "Insufficient factory stock for selected product. Requested: {$reqText}, Available: {$availText}.");
            }

            // Refund old product stock & deduct new product stock
            $this->adjustManufactureStock($mfg_id, (int)$oldRecord->product_id, $oldCases, $oldBottles);
            $this->adjustManufactureStock($mfg_id, (int)$request->product_id, -$newCases, -$newBottles);
        }

        $updateData = [
            'warehouse_id'      => $request->warehouse_id,
            'product_id'        => $request->product_id,
            'per_case_quantity' => $newCases,
            'bottles'           => $newBottles,
        ];
        if ($request->filled('assign_date')) {
            $assignDate = date('Y-m-d', strtotime($request->assign_date));
            $oldTime = $oldRecord->created_at ? date('H:i:s', strtotime($oldRecord->created_at)) : date('H:i:s');
            $updateData['assign_date'] = $assignDate;
            $updateData['created_at'] = $assignDate . ' ' . $oldTime;
        }

        WarehouseProduct::where('id', $request->warehouse_products_id)->update($updateData);

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