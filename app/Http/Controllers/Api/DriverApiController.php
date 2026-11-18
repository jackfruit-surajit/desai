<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Road;
use App\Models\DriverRoute;
use App\Models\Vehicle;
use App\Models\Area;
use App\Models\DriverPunchIn;
use App\Models\Customer;
use App\Models\ShopDeliveryHistory;
use App\Models\InvoiceHistory;
use App\Models\DriverAttendanceRouteLog;
use App\Models\UserBreakLog;
use App\Models\DriverProduct;
use App\Models\DriverProductDetails;
use App\Models\DriverReturn;
use App\Models\Product;
use App\Models\Admin;
use App\Models\Invoice;
use App\Models\InvoiceData;
use App\Models\ShopProductReplacement;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Hash;
use DB;

class DriverApiController extends Controller
{
    public function profile(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        $result = DB::table('admins')->where('id',$request->user_id)->first(['id','role_id','name','email','phone','image','licence_no','licence_expiry']);

        if($result){
            $result->image = url(asset_path('uploads/staff/'.$result->image));
            return response()->json(['status' => 200,'message' => 'Record found.', 'data'  =>$result,],200);
        }
        else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'data'  =>[],],200);
        }
    }
    
    public function UpdateProfile(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        $result = DB::table('admins')->where('id',$request->user_id)->where('role_id','3')
                ->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                ]);

        if($result){
            return response()->json(['status' => 200,'message' => 'Record updated successfully.', 'data'  =>$result,],200);
        }
        else{
            return response()->json(['status' => 200,'message' => 'Error!! while updating record.', 'data'  =>[],],200);
        }
    }
    
    public function driverPunchIn(Request $request){
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'selfie'  => 'required|file',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        $data = array('user_id' => $request->user_id, 'punch_in_time' => now(),);
        if ($request->hasFile('selfie')) {
                $sample_image = $request->file('selfie');
                $imagename = $this->rand_string(14) . '.' . $sample_image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/driver');
                $sample_image->move($destinationPath, $imagename);
                $data['selfie'] = $imagename;
        }
        
        $result = DriverPunchIn::create($data);
        if($result){
            return response()->json(['status' => 200,'message' => 'Punch in successfully.', 'data'  =>$result],200);
        }else{
            return response()->json(['status' => 500,'message' => 'unauthorized error.', 'data'  =>[]],500);
        }
    }
    
    public function driverPunchOut(Request $request){
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        if(DriverPunchIn::where('user_id',$request->user_id)->whereDate('created_at',today())->exists()){
            
            $result = DriverPunchIn::where('user_id', $request->user_id)
                ->whereDate('created_at', today())
                ->update([
                    'punch_out_time' => now(),
                ]);
    
            if($result){
                return response()->json(['status' => 200,'message' => 'Punch out successfully.', 'data'  =>$result],200);
            }else{
                return response()->json(['status' => 500,'message' => 'unauthorized error.', 'data'  =>[]],500);
            }
        }else{
            return response()->json(['status' => 200,'message' => 'Please Punch in your daliy profile.', 'data'  =>[]],200);
        }
        
    }
    
    public function driverBreak(Request $request){
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        if(DriverPunchIn::where('user_id',$request->user_id)->whereDate('created_at',today())->exists()){
            
            $result = DriverPunchIn::where('user_id', $request->user_id)
                ->whereDate('created_at', today())
                ->update([
                    'break_time' => now(),
                ]);

            UserBreakLog::create([
                    'user_id' => $request->user_id,
                    'break_start_time' => now(),
            ]);

            if($result){
                return response()->json(['status' => 200,'message' => 'Break time updated successfully.', 'data'  =>[]],200);
            }else{
                return response()->json(['status' => 500,'message' => 'unauthorized error.', 'data'  =>[]],500);
            }
        }else{
            return response()->json(['status' => 200,'message' => 'Please Punch in your daliy profile.', 'data'  =>[]],200);
        }
        
    }

    public function driverBreakEnd(Request $request){
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        if(DriverPunchIn::where('user_id',$request->user_id)->whereDate('created_at',today())->exists()){
            
            $result = DriverPunchIn::where('user_id', $request->user_id)
                ->whereDate('created_at', today())
                ->update([
                    'break_end' => now(),
                ]);

            UserBreakLog::where('user_id', $request->user_id)->whereNull('break_end_time')->latest()
            ->update([
                    'break_end_time' => now(),
            ]);
    
            if($result){
                return response()->json(['status' => 200,'message' => 'Break time updated successfully.', 'data'  =>[]],200);
            }else{
                return response()->json(['status' => 500,'message' => 'unauthorized error.', 'data'  =>[]],500);
            }
        }else{
            return response()->json(['status' => 200,'message' => 'Please Punch in your daliy profile.', 'data'  =>[]],200);
        }
        
    }

    public function checkBreakStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }

        $result = UserBreakLog::where('user_id', $request->user_id)->whereDate('created_at',date('Y-m-d'))->latest()->first();
        if($result && $result->break_start_time && empty($result->break_end_time)){
            return response()->json(['status' => 200,'message' => 'Record found.', 'break_status' =>'1', 'data'  =>$result],200);
        }
        else{
            return response()->json(['status' => 200,'message' => 'Record found.', 'break_status' =>'0', 'data'  =>$result],200);
        }
    }
    
    public function driverRoute(Request $request){
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        $result = DriverRoute::join('road','driver_route.road_id','road.id')
            ->where('driver_route.driver_id',$request->user_id)->orderBy('driver_route.serial_no','asc')->get(['driver_route.*','road.area_id','road.road_name','road.full_address']);
            
        if($result){
            return response()->json(['status' => 200,'message' => 'Record found.', 'data'  =>$result],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'data'  =>[]],200);
        }
    }
    
    public function routeDetails(Request $request){
        
        $validator = Validator::make($request->all(), [
            'route_id' => 'required|integer',
            'user_id'  => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        $result = Road::where('id',$request->route_id)->where('status','1')->first();
        
        if($request->user_id){
            if($res = DriverAttendanceRouteLog::where(['driver_id' => $request->user_id,'route_id' => $request->route_id])->whereDate('created_at',date('Y-m-d'))->first()){
                DriverAttendanceRouteLog::where('id',$res->id)->update(['driver_id' => $request->user_id,'route_id' => $request->route_id]);
            }
            else{
                DriverAttendanceRouteLog::create(['driver_id' => $request->user_id,'route_id' => $request->route_id]);
            } 
        }
            
        if($result){
            $shop_count = Customer::where('road_id',$request->route_id)->count();
            return response()->json(['status' => 200,'message' => 'Record found.', 'total_shop' => $shop_count, 'data'  =>$result,],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'data'  =>[], 'total_shop' =>'0',],200);
        }
    }
    
    public function routeShops(Request $request){
        
        $validator = Validator::make($request->all(), [
            'route_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        $result = Customer::where('customers.road_id',$request->route_id)->orderBy('customers.sequence','asc')->get(['customers.id','customers.name','customers.shop_name']);
        
        
        $route_details = Road::where('id',$request->route_id)->where('status','1')->first(['id','area_id','road_name','full_address']);
        
        if(count($result) > 0){
            $item = array();
            foreach($result as $val){
                
                if($dh = ShopDeliveryHistory::where('shop_id',$val->id)->whereDate('created_at',date('Y-m-d'))->first()){
                    if($dh->delivery_status == '1'){
                        $val['status'] = 'Delivered'; 
                    }
                    elseif($dh->delivery_status == '2'){
                        $val['status'] = 'Shop closed  never retrun'; 
                    }
                    else{
                        $val['status'] = 'Shop closed need to retun'; 
                    }
                }
                else{
                    $val['status'] = 'Delivery Pending'; 
                }
                
                $item[] = $val;
            }
            
            return response()->json(['status' => 200,'message' => 'Record found.', 'total_shop' => count($result), 'route_details' => $route_details, 'shop_list'  =>$item,],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'total_shop' => count($result), 'route_details' => [], 'shop_list'  =>[],],200);
        }
    }
    
    public function addCustomer(Request $request){
        $validator = Validator::make($request->all(), [
            'route_id' => 'required|integer',
            'shop_name' => 'required|string',
            'shop_photo' => 'required|file',
            'owner_name' => 'required|string',
            // 'address' => 'nullable|string',
            'mobile_no' =>'required|string',
        
            'landmark' =>'required|string',
            'state' =>'required|string',
            'city' =>'required|string',
            'pin_code' =>'required|string',
            'pan_no' =>'nullable|string',
            'gst_no' =>'nullable|string',
            'email' =>'nullable|email',
            
            'latitude' =>'nullable|string',
            'longitude' =>'nullable|string',
            'driver_id' =>'required|integer',
            'previous_shop_id' =>'nullable|integer'
        ]);

        
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        if($request->previous_shop_id){
            $previous_shop = Customer::where('id',$request->previous_shop_id)->first(['sequence']);
            $sequence = $previous_shop->sequence;
        }
        else{
            if($previous_shop = Customer::latest()->first(['sequence'])){
              $sequence = $previous_shop->sequence + 1;  
            }
            else{
                $sequence = 1;
            }
        }
        
        
        
        if($road = Road::where('id',$request->route_id)->first(['id','road_name','area_id'])){
            if($area = Area::where('id',$road->area_id)->first(['area_name'])){
                $address = $road->road_name.','.$area->area_name.','.$request->landmark.','.$request->state.','.$request->city.','.$request->pin_code;
            }else{
               $address = $road->road_name.','.$request->landmark.','.$request->state.','.$request->city.','.$request->pin_code; 
            }
            
        }
        else{
            $address = $road->road_name.','.$request->landmark.','.$request->state.','.$request->city.','.$request->pin_code;
        }
            
        
        
        $data = array(
                'shop_name' => $request->shop_name,
                'road_id' => $request->route_id,
                'name' => $request->owner_name,
                'address' => $address,
                'mobile_no' => $request->mobile_no,
                'created_by' => $request->driver_id,
                'landmark' => $request->landmark,
                'state' => $request->state,
                'city' => $request->city,
                'pin_code' => $request->pin_code,
                'pan_no' => $request->pan_no,
                'gst_no' => $request->gst_no,
                'email' => $request->email,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'sequence' => $sequence,
            );
            
            
        if ($request->hasFile('shop_photo')) {
                $sample_image = $request->file('shop_photo');
                $imagename = $this->rand_string(14) . '.' . $sample_image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/customer');
                $sample_image->move($destinationPath, $imagename);
                $data['shop_photo'] = $imagename;
        }
        
        $result = Customer::create($data);
        
        if($result){
            // unset($previous_shop,$sequence,$road,$area,$data);
            return response()->json(['status' => 200,'message' => 'Customer added successfully.', 'data'  =>$result,],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Error!! while adding customer.', 'data' => [],],200);
        }
    }

    public function customerUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'pan_no' =>'nullable|string',
            'gst_no' =>'nullable|string',
            'email' =>'nullable|email',
            'latitude' =>'nullable|string',
            'longitude' =>'nullable|string',
            'shop_id' =>'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }

        $data = array(
            'pan_no' => $request->pan_no,
            'gst_no' => $request->gst_no,
            'email' => $request->email,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        );

        $result = Customer::where('shop_id',$request->shop_id)->update($data);
        
        if($result){
            return response()->json(['status' => 200,'message' => 'Customer added successfully.', 'data'  =>$result,],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Error!! while adding customer.', 'data' => [],],200);
        }

    }
    
    
    public function customerDetails(Request $request){
        
        $validator = Validator::make($request->all(), [
            'shop_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        $result = Customer::where('id',$request->shop_id)->where('status','1')->first();

        if($result){
            
            if($invoice = InvoiceHistory::where('shop_id',$request->shop_id)->whereDate('created_at',date('Y-m-d'))->orderBy('id','desc')->first()){
               $result->invoice = $invoice->path;
            }else{
                $result->invoice = '';
            }
            
            return response()->json(['status' => 200,'message' => 'Record found.', 'image_url' => url(asset_path('uploads/customer/')), 'data'  =>$result,],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'data'  =>[],],200);
        }
    }
    
    // private function checkDeliverdStatus($shop_id){
        
    //     if($dh = ShopDeliveryHistory::where('shop_id',$shop_id)->whereDate('created_at',date('Y-m-d'))->first()){
    //         if($dh->delivery_status == '1'){
    //                     $val['status'] = 'Delivered'; 
    //         }
    //         elseif($dh->delivery_status == '2'){
    //                     $val['status'] = 'Closed'; 
    //         }
    //     }
    //     else{
    //         $val['status'] = 'Delivery Pending'; 
    //     }
    // }
    
    public function updateDelivaryStatus(Request $request){
        
        $rules = array(
            'shop_id' => 'required|integer',
            'driver_id' => 'required|integer',
            'status' => 'required',
            'delivery_date' => 'required|date',
        );
        
        if($request->status =='1'){
            $rules['selfie'] = 'required|file';
            $rules['delivery_note'] = 'nullable|string';
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        
            $data = array(
                    'shop_id' => $request->shop_id,
                    'driver_id' => $request->driver_id,
                    'delivery_date' => $request->delivery_date,
                    'delivery_status' => $request->status,
                );
            
            if($request->status =='1'){
                
                if ($request->hasFile('selfie')) {
                    $sample_image = $request->file('selfie');
                    $imagename = $this->rand_string(14) . '.' . $sample_image->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/driver/shop_photo');
                    $sample_image->move($destinationPath, $imagename);
                    $data['selfie'] = $imagename;
                }
                
                $data['delivery_note'] = $request->delivery_note;
            }
            
        
        $check_delivary = ShopDeliveryHistory::where('shop_id',$request->shop_id)
                        ->where('driver_id',$request->driver_id)->whereDate('delivery_date',date('Y-m-d'))->first();
        if($check_delivary){
            $result = ShopDeliveryHistory::where('id',$check_delivary->id)->update($data);
        }
        else{
            $result = ShopDeliveryHistory::create($data);
        }
        
        if($result && $request->status =='1'){
            return response()->json(['status' => 200,'message' => 'Product Delivery completed.','data'  =>$result,],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Shop was closed.', 'data'  =>[],],200);
        }
    }
    
    
    public function vehicleDetails(Request $request){
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        $result = Vehicle::where('id',$request->vehicle_id)->where('status','1')->first();

        if($result){
            return response()->json(['status' => 200,'message' => 'Record found.', 'data'  =>$result,],200);
        }
        else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'data'  =>[],],200);
        }
    }
    
    public function driverCompletedDeliveries(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        
        
        $result = ShopDeliveryHistory::join('customers','shop_delivery_history.shop_id','customers.id')
                    ->where('shop_delivery_history.driver_id',$request->user_id)
                    ->where('shop_delivery_history.delivery_status','1')
                    ->whereDate('shop_delivery_history.delivery_date',$request->date)
                    ->get(['shop_delivery_history.*','customers.name','customers.shop_name','customers.address']);

        if($result){
            return response()->json(['status' => 200,'message' => 'Record found.', 'data'  =>$result,],200);
        }
        else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'data'  =>[],],200);
        }
    }
    
    public function shopSequence(Request $request){
        
        $validator = Validator::make($request->all(), [
                'driver_id'        => 'required|integer|exists:admins,id',
                'shops'            => 'required|array|min:1',
                'shops.*.shop_id'  => 'required|integer|distinct|exists:customers,id',
                'shops.*.sequence' => 'required|integer|min:1',
            ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        foreach($request->shops as $shop){
            Customer::where('created_by',$request->driver_id)->where('id',$shop['shop_id'])->update([
                'sequence' => $shop['sequence'],
            ]);
        }
        return response()->json(['status' => 200,'message' => 'Record updated successfully.', 'data'  =>[],],200);
        
    }
    
    
    public function routeShopsFilter(Request $request){
        
        $validator = Validator::make($request->all(), [
            'route_id' => 'required|integer',
            'keyword' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        $result = Customer::where('customers.road_id',$request->route_id)
                ->whereLike('shop_name', '%'.$request->keyword.'%')
                ->orderBy('customers.sequence','asc')->get(['customers.id','customers.name','customers.shop_name']);
        
        
        if(count($result) > 0){
            
            return response()->json(['status' => 200,'message' => 'Record found.', 'total_shop' => count($result), 'shop_list'  =>$result,],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'total_shop' => count($result), 'route_details' => [], 'shop_list'  =>[],],200);
        }
    }
    
    public function driverAttendance(Request $request){
        
        $validator = Validator::make($request->all(), [
                'user_id'        => 'required|integer|exists:admins,id',
            ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        if($driver_atten = DriverPunchIn::where('user_id',$request->user_id)->whereDate('created_at',date('Y-m-d'))->first()){
            
            if($driver_atten->punch_in_time && $driver_atten->punch_out_time){
                return response()->json(['status' => 200,'message' => 'Record found.', 'panch_in_status' => 'logout', 'data'  =>$driver_atten,],200); 
            }
            elseif($driver_atten->punch_in_time){
                return response()->json(['status' => 200,'message' => 'Record found.', 'panch_in_status' => 'active', 'data'  =>$driver_atten,],200); 
            }
            else{
                return response()->json(['status' => 200,'message' => 'Record not found.', 'panch_in_status' => 'inactive', 'data'  =>$driver_atten,],200);
            }
        }
        else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'panch_in_status' => 'inactive', 'data'  =>[],],200);
        }
    }

    /**
     * Get list of products assigned to a driver for a specific date (or latest date).
     * Accepts POST or GET with driver_id or user_id, and optional date / assignment_id.
     */
    public function assignedProducts(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'        => 'required|integer|exists:admins,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }

        $driverId = $request->input('user_id');
        $today = date('Y-m-d');
        $result = DriverProduct::join('driver_product_details','driver_products.id','driver_product_details.driver_products_id')
                ->join('products','driver_product_details.product_id','products.id')
                ->where('driver_products.driver_id', $driverId)
                ->whereDate('driver_products.assign_date',$today)
                ->get(['driver_product_details.id','driver_product_details.quantity as case','driver_product_details.bottles','products.id as product_id','products.name','products.hsn_code','driver_product_details.status','driver_product_details.comment']);
        return response()->json([
            'status' => 200,
            'message' => 'Driver assigned products fetched successfully.',
            'total_item' => count($result),
            'data' => $result,
        ], 200);
    }

    public function acceptProducts(Request $request){
        $validator = Validator::make($request->all(), [
                'user_id'        => 'required|integer|exists:admins,id',
                'product_details_id' => 'required|integer',
                'status' => 'required|integer',
                'comment' => 'nullable|string',
            ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }

        $result = DriverProductDetails::where('id',$request->input('product_details_id'))->where('driver_id',$request->input('user_id'))
                ->update([
                    'status' => $request->input('status'),
                    'comment' => $request->input('comment'),
                ]);

        if($result){
            return response()->json(['status' => 200, 'message' => 'Products verified successfully.','data' => [],], 200);
        }
        return response()->json(['status' => 400, 'message' => 'Failed to verify products.','data' => [],], 400);
    }

   public function storeShopProductReplacement(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'                => 'required|integer|exists:admins,id',
            'shop_id'                => 'required|integer|exists:customers,id',
            'replace_date'           => 'required|date',
            'reason'                 => 'required|string',
            'product_id'             => 'required|integer',
            'cases'                  => 'required|integer',
            'bottles'                => 'required|integer',
            // 'image'                  => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }

        $result = ShopProductReplacement::create([
                    'shop_id' => $request->shop_id,
                    'driver_id' => $request->user_id,
                    'replace_date' => $request->replace_date,
                    'product_id' => $request->product_id,
                    'cases' => $request->cases,
                    'bottles' => $request->bottles,
                    'reason' => $request->reason,
                ]);

        if($result){
            return response()->json(['status' => 200, 'message' => 'Products Replaced successfully.','data' => [],], 200);
        }
        else{
             return response()->json(['status' => 400, 'message' => 'Unable to replaced products.','data' => [],], 400);
        }
        
    }


    public function driverReplaceProductList(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:admins,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }

        $result = ShopProductReplacement::join('products','shop_product_replacements.product_id','products.id')
                    ->join('customers','shop_product_replacements.shop_id','customers.id')->whereDate('shop_product_replacements.created_at',date('Y-m-d'))
                    ->where('shop_product_replacements.driver_id',$request->user_id)->get(['shop_product_replacements.*','customers.shop_name','customers.mobile_no','products.name as product_name']);

        if($result){
            return response()->json(['status' => 200, 'message' => 'Products Replaced successfully.','data' => $result,], 200);
        }
        else{
             return response()->json(['status' => 400, 'message' => 'Unable to replaced products.','data' => [],], 400);
        }
    }
    
    public function dailySummary(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'   => 'nullable|integer|exists:admins,id',
            'driver_id' => 'nullable|integer|exists:admins,id',
            'date'      => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }

        $driverId = $request->input('user_id', $request->input('driver_id'));
        $reportDate = $request->filled('date') ? $request->date : date('Y-m-d');

        // All active products
        $products = Product::where('status', '1')->get()->keyBy('id');

        // 1. Assigned Stock on this date
        $assignedRows = DB::table('driver_products')
            ->join('driver_product_details', 'driver_products.id', '=', 'driver_product_details.driver_products_id')
            ->where('driver_products.driver_id', $driverId)
            ->where(function ($q) use ($reportDate) {
                $q->whereDate('driver_products.assign_date', $reportDate)
                  ->orWhere(function ($sub) use ($reportDate) {
                      $sub->whereNull('driver_products.assign_date')
                          ->whereDate('driver_products.created_at', $reportDate);
                  });
            })
            ->select([
                'driver_product_details.product_id',
                DB::raw('SUM(driver_product_details.quantity) as total_cases'),
                DB::raw('SUM(driver_product_details.bottles) as total_bottles')
            ])
            ->groupBy('driver_product_details.product_id')
            ->get()
            ->keyBy('product_id');

        // 2. Invoices (Deliveries made on this date)
        $invoices = Invoice::where('driver_id', $driverId)
            ->whereDate('created_at', $reportDate)
            ->get();

        $invoiceCount = $invoices->count();
        $invoiceTotalAmount = (float)$invoices->sum('total_price');

        $soldRows = DB::table('invoice')
            ->join('invoice_data', 'invoice.id', '=', 'invoice_data.invoice_id')
            ->where('invoice.driver_id', $driverId)
            ->whereDate('invoice.created_at', $reportDate)
            ->select([
                'invoice_data.product_id',
                DB::raw('SUM(invoice_data.quantity) as sold_bottles')
            ])
            ->groupBy('invoice_data.product_id')
            ->get()
            ->keyBy('product_id');

        // 3. Physical Returns recorded by warehouse
        $manualReturns = DB::table('driver_returns')
            ->where('driver_id', $driverId)
            ->whereDate('return_date', $reportDate)
            ->select([
                'product_id',
                DB::raw('SUM(cases) as total_cases'),
                DB::raw('SUM(bottles) as total_bottles')
            ])
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $hasManualReturn = $manualReturns->isNotEmpty();

        // 4. Replacements on this date
        $replacementRows = DB::table('shop_product_replacements')
            ->where('driver_id', $driverId)
            ->where(function ($q) use ($reportDate) {
                $q->whereDate('replace_date', $reportDate)
                  ->orWhere(function ($sub) use ($reportDate) {
                      $sub->whereNull('replace_date')
                          ->whereDate('created_at', $reportDate);
                  });
            })
            ->select([
                'product_id',
                DB::raw('COUNT(id) as total_records'),
                DB::raw('SUM(cases) as total_cases'),
                DB::raw('SUM(bottles) as total_bottles')
            ])
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $replacementList = ShopProductReplacement::join('products', 'shop_product_replacements.product_id', '=', 'products.id')
            ->join('customers', 'shop_product_replacements.shop_id', '=', 'customers.id')
            ->where('shop_product_replacements.driver_id', $driverId)
            ->where(function ($q) use ($reportDate) {
                $q->whereDate('shop_product_replacements.replace_date', $reportDate)
                  ->orWhere(function ($sub) use ($reportDate) {
                      $sub->whereNull('shop_product_replacements.replace_date')
                          ->whereDate('shop_product_replacements.created_at', $reportDate);
                  });
            })
            ->select([
                'shop_product_replacements.id',
                'shop_product_replacements.shop_id',
                'customers.shop_name',
                'customers.name as customer_name',
                'customers.mobile_no',
                'shop_product_replacements.product_id',
                'products.name as product_name',
                'shop_product_replacements.cases',
                'shop_product_replacements.bottles',
                'shop_product_replacements.reason',
                'shop_product_replacements.replace_date',
                'shop_product_replacements.created_at'
            ])
            ->orderBy('shop_product_replacements.id', 'desc')
            ->get();

        // All distinct product IDs involved
        $allProductIds = $assignedRows->keys()
            ->merge($soldRows->keys())
            ->merge($manualReturns->keys())
            ->merge($replacementRows->keys())
            ->unique();

        $productList = [];
        $overallAssignedBottles = 0;
        $overallAssignedCases = 0;
        $overallAssignedLoose = 0;

        $overallSoldBottles = 0;
        $overallSoldCases = 0;
        $overallSoldLoose = 0;

        $overallUnsoldBottles = 0;
        $overallUnsoldCases = 0;
        $overallUnsoldLoose = 0;

        $overallReturnedBottles = 0;
        $overallReturnedCases = 0;
        $overallReturnedLoose = 0;

        $overallReplacementBottles = 0;
        $overallReplacementCases = 0;
        $overallReplacementLoose = 0;
        $totalReplacementCount = $replacementList->count();

        $calculatedRevenue = 0;

        foreach ($allProductIds as $pid) {
            $p = $products->get($pid) ?? Product::find($pid);
            if (!$p) continue;

            $bpc = ($p->per_case_quantity > 0) ? (int)$p->per_case_quantity : 24;
            $bottlePrice = (float)($p->per_bottle_price ?? 0);
            $casePrice = (float)($p->per_case_price ?? ($bottlePrice * $bpc));

            // Assigned
            $aRow = $assignedRows->get($pid);
            $aCases = $aRow ? (int)$aRow->total_cases : 0;
            $aLoose = $aRow ? (int)$aRow->total_bottles : 0;
            $pAssignedBottles = ($aCases * $bpc) + $aLoose;

            // Returned
            $rRow = $manualReturns->get($pid);
            $rCases = ($hasManualReturn && $rRow) ? (int)$rRow->total_cases : 0;
            $rLoose = ($hasManualReturn && $rRow) ? (int)$rRow->total_bottles : 0;
            $pReturnedBottles = ($rCases * $bpc) + $rLoose;

            // Replacements
            $repRow = $replacementRows->get($pid);
            $repCases = $repRow ? (int)$repRow->total_cases : 0;
            $repLoose = $repRow ? (int)$repRow->total_bottles : 0;
            $pReplacementBottles = ($repCases * $bpc) + $repLoose;
            $repCount = $repRow ? (int)$repRow->total_records : 0;

            // Sold
            $sRow = $soldRows->get($pid);
            $invBottles = $sRow ? (int)$sRow->sold_bottles : 0;

            if ($hasManualReturn) {
                $pSoldBottles = max(0, $pAssignedBottles - $pReturnedBottles);
                if ($pSoldBottles == 0 && $pAssignedBottles == 0 && $invBottles > 0) {
                    $pSoldBottles = $invBottles;
                }
            } else {
                $pSoldBottles = $invBottles;
            }

            $sCases = intdiv($pSoldBottles, $bpc);
            $sLoose = $pSoldBottles % $bpc;

            // Unsold / Not Sold:
            if ($hasManualReturn) {
                $pUnsoldBottles = $pReturnedBottles;
            } else {
                $pUnsoldBottles = max(0, $pAssignedBottles - $pSoldBottles);
            }

            $uCases = intdiv($pUnsoldBottles, $bpc);
            $uLoose = $pUnsoldBottles % $bpc;

            $itemRevenue = $pSoldBottles * $bottlePrice;
            $calculatedRevenue += $itemRevenue;

            // Accumulate totals
            $overallAssignedBottles += $pAssignedBottles;
            $overallAssignedCases += $aCases;
            $overallAssignedLoose += $aLoose;

            $overallSoldBottles += $pSoldBottles;
            $overallSoldCases += $sCases;
            $overallSoldLoose += $sLoose;

            $overallUnsoldBottles += $pUnsoldBottles;
            $overallUnsoldCases += $uCases;
            $overallUnsoldLoose += $uLoose;

            $overallReturnedBottles += $pReturnedBottles;
            $overallReturnedCases += $rCases;
            $overallReturnedLoose += $rLoose;

            $overallReplacementBottles += $pReplacementBottles;
            $overallReplacementCases += $repCases;
            $overallReplacementLoose += $repLoose;

            $productList[] = [
                'product_id'        => $p->id,
                'product_name'      => $p->name,
                'hsn_code'          => $p->hsn_code ?? 'N/A',
                'bottles_per_case'  => $bpc,
                'bottle_price'      => $bottlePrice,
                'case_price'        => $casePrice,

                // 1. Assign stock count
                'assigned' => [
                    'cases'         => $aCases,
                    'bottles'       => $aLoose,
                    'total_bottles' => $pAssignedBottles,
                    'text'          => $this->formatStockText($aCases, $aLoose),
                ],

                // 2. Koto gulo sale hoyache
                'sold' => [
                    'cases'         => $sCases,
                    'bottles'       => $sLoose,
                    'total_bottles' => $pSoldBottles,
                    'text'          => $this->formatStockText($sCases, $sLoose),
                    'sales_amount'  => round($itemRevenue, 2),
                ],

                // 3. Koto gulo sale hoini
                'unsold' => [
                    'cases'         => $uCases,
                    'bottles'       => $uLoose,
                    'total_bottles' => $pUnsoldBottles,
                    'text'          => $this->formatStockText($uCases, $uLoose),
                ],

                // Physical returned
                'returned' => [
                    'cases'         => $rCases,
                    'bottles'       => $rLoose,
                    'total_bottles' => $pReturnedBottles,
                    'text'          => $this->formatStockText($rCases, $rLoose),
                ],

                // 4. Koto gulo replacement hoyache
                'replacement' => [
                    'count'         => $repCount,
                    'cases'         => $repCases,
                    'bottles'       => $repLoose,
                    'total_bottles' => $pReplacementBottles,
                    'text'          => $this->formatStockText($repCases, $repLoose),
                ],
            ];
        }

        $totalSalesAmount = ($hasManualReturn || $invoiceTotalAmount == 0)
            ? round($calculatedRevenue, 2)
            : round($invoiceTotalAmount, 2);

        // Overall summary structure
        $summary = [

            'assign_stock' => [
                'total_assigned_products'      => count($assignedRows),
                'total_assigned_cases'         => $overallAssignedCases,
                'total_assigned_bottles'       => $overallAssignedLoose,
                // 'total_assigned_total_bottles' => $overallAssignedBottles,
                // 'assigned_text'                => $this->formatStockText($overallAssignedCases, $overallAssignedLoose),
            ],

            // 2. Koto gulo product sale hoyache

            'sold_stock' => [
                'total_sold_cases'             => $overallSoldCases,
                'total_sold_bottles'           => $overallSoldLoose,
                // 'total_sold_total_bottles'     => $overallSoldBottles,
                // 'sold_text'                    => $this->formatStockText($overallSoldCases, $overallSoldLoose),
                // 'total_sales_amount'           => $totalSalesAmount,
                // 'total_invoices'               => $invoiceCount,
            ],

            // 3. Koto gulo sale hoini
            'unsold_stock' => [
                'total_unsold_cases'           => $overallUnsoldCases,
                'total_unsold_bottles'         => $overallUnsoldLoose,
                // 'total_unsold_total_bottles'   => $overallUnsoldBottles,
                // 'unsold_text'                  => $this->formatStockText($overallUnsoldCases, $overallUnsoldLoose),
            ],

            // Returned stock
            // 'total_returned_cases'         => $overallReturnedCases,
            // 'total_returned_bottles'       => $overallReturnedLoose,
            // 'total_returned_total_bottles' => $overallReturnedBottles,
            // 'returned_text'                => $this->formatStockText($overallReturnedCases, $overallReturnedLoose),

            // 4. Koto gulo replacement hoyache

            'replacement_stock' => [
                // 'total_replacement_records'       => $totalReplacementCount,
                'total_replacement_cases'         => $overallReplacementCases,
                'total_replacement_bottles'       => $overallReplacementLoose,
                // 'total_replacement_total_bottles' => $overallReplacementBottles,
                // 'replacement_text'                => $this->formatStockText($overallReplacementCases, $overallReplacementLoose),
            ],
        ];

        return response()->json([
            'status'               => 200,
            'message'              => 'Driver daily summary fetched successfully.',
            'date'                 => $reportDate,
            'summary'              => $summary,
            // 'products'             => $productList,
            // 'replacement_details'  => $replacementList,
        ], 200);
    }

    private function formatStockText($cases, $bottles){
        $cases = (int)$cases;
        $bottles = (int)$bottles;
        if ($cases > 0 && $bottles > 0) {
            return "{$cases} Cases + {$bottles} Bottles";
        } elseif ($cases > 0) {
            return "{$cases} Cases";
        } elseif ($bottles > 0) {
            return "{$bottles} Bottles";
        }
        return "0 Cases";
    }
    
    public function acceptAssignedProducts(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'        => 'required|integer|exists:admins,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }

        $driverId = $request->input('user_id');
        $today = date('Y-m-d');
        $result = DriverProduct::join('driver_product_details','driver_products.id','driver_product_details.driver_products_id')
                ->join('products','driver_product_details.product_id','products.id')
                ->where('driver_products.driver_id', $driverId)
                ->whereDate('driver_products.assign_date',$today)
                ->where('driver_product_details.status','1')
                ->get(['driver_product_details.id','driver_product_details.quantity as case','driver_product_details.bottles','products.id as product_id','products.name','products.hsn_code','driver_product_details.status','driver_product_details.comment']);
        return response()->json([
            'status' => 200,
            'message' => 'Driver assigned products fetched successfully.',
            'total_item' => count($result),
            'data' => $result,
        ], 200);
    }
    
    public function driverProductStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'nullable|integer|exists:admins,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 422,
                'message' => $validator->errors(),
                'data'    => []
            ], 422);
        }

        $driverId = $request->input('user_id');
        if (!$driverId) {
            return response()->json([
                'status'  => 422,
                'message' => 'The user_id or driver_id field is required.',
                'data'    => []
            ], 422);
        }

        $reportDate = date('Y-m-d');
        $driver = Admin::find($driverId);

        // All active products
        $products = Product::where('status', '1')->get()->keyBy('id');

        // 1. Assigned Stock on this date
        $assignedRows = DB::table('driver_products')
            ->join('driver_product_details', 'driver_products.id', '=', 'driver_product_details.driver_products_id')
            ->where('driver_products.driver_id', $driverId)
            ->where('driver_product_details.status', '1')
            ->where(function ($q) use ($reportDate) {
                $q->whereDate('driver_products.assign_date', $reportDate)
                  ->orWhere(function ($sub) use ($reportDate) {
                      $sub->whereNull('driver_products.assign_date')
                          ->whereDate('driver_products.created_at', $reportDate);
                  });
            })
            ->select([
                'driver_product_details.product_id',
                DB::raw('SUM(driver_product_details.quantity) as total_cases'),
                DB::raw('SUM(driver_product_details.bottles) as total_bottles')
            ])
            ->groupBy('driver_product_details.product_id')
            ->get()
            ->keyBy('product_id');

        // 2. Invoices (Deliveries / Sales made on this date)
        $soldRows = DB::table('invoice')
            ->join('invoice_data', 'invoice.id', '=', 'invoice_data.invoice_id')
            ->where('invoice.driver_id', $driverId)
            ->whereDate('invoice.created_at', $reportDate)
            ->select([
                'invoice_data.product_id',
                DB::raw('SUM(invoice_data.quantity) as sold_bottles')
            ])
            ->groupBy('invoice_data.product_id')
            ->get()
            ->keyBy('product_id');

        // 3. Physical Returns recorded by warehouse
        $manualReturns = DB::table('driver_returns')
            ->where('driver_id', $driverId)
            ->whereDate('return_date', $reportDate)
            ->select([
                'product_id',
                DB::raw('SUM(cases) as total_cases'),
                DB::raw('SUM(bottles) as total_bottles'),
                DB::raw('MAX(is_carry_forward) as is_carry_forward'),
                DB::raw('MAX(is_return) as is_return')
            ])
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $hasManualReturn = $manualReturns->isNotEmpty();

        // Distinct product IDs involved
        $allProductIds = $assignedRows->keys()
            ->merge($soldRows->keys())
            ->merge($manualReturns->keys())
            ->unique();

        $unsoldList = [];
        $totalUnsoldBottlesSum = 0;
        $returnAll = $request->boolean('all');

        foreach ($allProductIds as $pid) {
            $p = $products->get($pid) ?? Product::find($pid);
            if (!$p) continue;

            $bpc = ($p->per_case_quantity > 0) ? (int)$p->per_case_quantity : 24;
            $bottlePrice = (float)($p->per_bottle_price ?? 0);
            $casePrice = (float)($p->per_case_price ?? ($bottlePrice * $bpc));

            // Assigned
            $aRow = $assignedRows->get($pid);
            $aCases = $aRow ? (int)$aRow->total_cases : 0;
            $aLoose = $aRow ? (int)$aRow->total_bottles : 0;
            $pAssignedBottles = ($aCases * $bpc) + $aLoose;

            // Physical Return
            $rRow = $manualReturns->get($pid);
            $rCases = ($hasManualReturn && $rRow) ? (int)$rRow->total_cases : 0;
            $rLoose = ($hasManualReturn && $rRow) ? (int)$rRow->total_bottles : 0;
            $pReturnedBottles = ($rCases * $bpc) + $rLoose;
            $isCarryForward = ($rRow && (int)$rRow->is_carry_forward === 1) ? 1 : 0;
            $isReturn = ($rRow && (int)$rRow->is_return === 1) ? 1 : 0;

            // Sold
            $sRow = $soldRows->get($pid);
            $invBottles = $sRow ? (int)$sRow->sold_bottles : 0;

            if ($hasManualReturn) {
                $pSoldBottles = max(0, $pAssignedBottles - $pReturnedBottles);
                if ($pSoldBottles == 0 && $pAssignedBottles == 0 && $invBottles > 0) {
                    $pSoldBottles = $invBottles;
                }
            } else {
                $pSoldBottles = $invBottles;
            }

            $sCases = intdiv($pSoldBottles, $bpc);
            $sLoose = $pSoldBottles % $bpc;

            // Unsold:
            if ($hasManualReturn) {
                $pUnsoldBottles = $pReturnedBottles;
            } else {
                $pUnsoldBottles = max(0, $pAssignedBottles - $pSoldBottles);
            }

            // Unless 'all=1' was passed, skip products with zero unsold quantity
            if (!$returnAll && $pUnsoldBottles <= 0) {
                continue;
            }

            $uCases = intdiv($pUnsoldBottles, $bpc);
            $uLoose = $pUnsoldBottles % $bpc;

            $totalUnsoldBottlesSum += $pUnsoldBottles;

            $unsoldList[] = [
                'product_id'             => $p->id,
                'product_name'           => $p->name,
                'hsn_code'               => $p->hsn_code ?? 'N/A',
                'unsold_cases'           => $uCases,
                'unsold_bottles'         => $uLoose,
            ];
        }

        // Compute total unsold cases and loose bottles across all items
        $totalUnsoldCases = 0;
        $totalUnsoldLoose = 0;
        foreach ($unsoldList as $item) {
            $totalUnsoldCases += $item['unsold_cases'];
            $totalUnsoldLoose += $item['unsold_bottles'];
        }

        $replacement_products = ShopProductReplacement::join('products','shop_product_replacements.product_id','products.id')
                    ->join('customers','shop_product_replacements.shop_id','customers.id')->whereDate('shop_product_replacements.created_at',date('Y-m-d'))
                    ->where('shop_product_replacements.driver_id',$request->user_id)->get(['shop_product_replacements.*','customers.shop_name','customers.mobile_no','products.name as product_name']);

        $accepted_products = DriverProduct::join('driver_product_details','driver_products.id','driver_product_details.driver_products_id')
                ->join('products','driver_product_details.product_id','products.id')
                ->where('driver_products.driver_id', $request->user_id)
                ->whereDate('driver_products.assign_date',date('Y-m-d'))
                ->where('driver_product_details.status','1')
                ->get(['driver_product_details.id','driver_product_details.quantity as case','driver_product_details.bottles','products.id as product_id','products.name','products.hsn_code','driver_product_details.status','driver_product_details.comment']);

        return response()->json([
            'status'                     => 200,
            'message'                    => 'Driver unsold products fetched successfully.',
            'accepted_products'       => $accepted_products,
            'replacement_products'       => $replacement_products,
            'unsold_product'             => $unsoldList,
        ], 200);
    }

    public function productList(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }

        $result = DriverProduct::join('driver_product_details','driver_products.id','driver_product_details.driver_products_id')
                    ->join('products','driver_product_details.product_id','products.id')
                    ->where('driver_products.driver_id', $request->user_id)
                    ->whereDate('driver_products.assign_date',date('Y-m-d'))
                    ->where('driver_product_details.status','1')
                    ->get(['products.id as product_id','driver_product_details.quantity as case','driver_product_details.bottles','products.name','products.hsn_code','products.per_case_price','products.per_bottle_price']);

        if(count($result) > 0){
            return response()->json(['status' => 200,'message' => 'Record Found.', 'data'  =>$result],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Record Not Found.', 'data'  =>[]],200);
        }
    }
    
    
}