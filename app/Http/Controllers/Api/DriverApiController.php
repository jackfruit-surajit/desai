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
use App\Models\ShopProductReplacementDetails;
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
            $previous_shop = Customer::latest()->first(['sequence']);
            $sequence = $previous_shop->sequence + 1;
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

    /**
     * Store a product replacement transaction when a shop returns defective/damaged product to the driver.
     * Supports both single-product flat fields and multi-product arrays.
     */
    public function storeShopProductReplacement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'                => 'nullable|integer',
            'driver_id'              => 'nullable|integer',
            'shop_id'                => 'nullable|integer',
            'customer_id'            => 'nullable|integer',
            'warehouse_id'           => 'nullable|integer',
            'replace_date'           => 'nullable|date',
            'date'                   => 'nullable|date',
            'status'                 => 'nullable|string',
            'reason'                 => 'nullable|string',
            'notes'                  => 'nullable|string',
            'product_id'             => 'nullable|integer',
            'cases'                  => 'nullable|integer',
            'bottles'                => 'nullable|integer',
            'replacement_product_id' => 'nullable|integer',
            'replacement_cases'      => 'nullable|integer',
            'replacement_bottles'    => 'nullable|integer',
            'image'                  => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors(),
                'data' => []
            ], 422);
        }

        // Driver ID
        $driverId = $request->input('driver_id', $request->input('user_id'));
        if (!$driverId) {
            return response()->json([
                'status' => 422,
                'message' => 'Driver ID (driver_id or user_id) is required.',
                'data' => []
            ], 422);
        }

        $driver = DB::table('admins')->where('id', $driverId)->first();
        if (!$driver) {
            return response()->json([
                'status' => 404,
                'message' => 'Driver not found.',
                'data' => []
            ], 404);
        }

        // Shop ID
        $shopId = $request->input('shop_id', $request->input('customer_id'));
        if (!$shopId) {
            return response()->json([
                'status' => 422,
                'message' => 'Shop ID (shop_id or customer_id) is required.',
                'data' => []
            ], 422);
        }

        $shop = Customer::find($shopId);
        if (!$shop) {
            return response()->json([
                'status' => 404,
                'message' => 'Shop / Customer not found.',
                'data' => []
            ], 404);
        }

        // Warehouse ID
        $warehouseId = $request->input('warehouse_id');
        if (!$warehouseId) {
            $warehouseId = DB::table('driver_warehouse')->where('driver_id', $driverId)->value('warehouse_id');
        }

        $replaceDate = $request->input('replace_date', $request->input('date', date('Y-m-d')));

        // Parse items (multi-product or single flat product)
        $rawProducts = $request->input('products', $request->input('items'));
        if (is_string($rawProducts)) {
            $decoded = json_decode($rawProducts, true);
            if (is_array($decoded)) {
                $rawProducts = $decoded;
            }
        }

        $items = [];
        if (is_array($rawProducts) && count($rawProducts) > 0) {
            foreach ($rawProducts as $it) {
                if (!empty($it['product_id'])) {
                    $prodId = (int)$it['product_id'];
                    $replId = !empty($it['replacement_product_id']) ? (int)$it['replacement_product_id'] : $prodId;
                    $c = isset($it['cases']) ? (int)$it['cases'] : 0;
                    $b = isset($it['bottles']) ? (int)$it['bottles'] : 0;
                    $rc = isset($it['replacement_cases']) ? (int)$it['replacement_cases'] : $c;
                    $rb = isset($it['replacement_bottles']) ? (int)$it['replacement_bottles'] : $b;
                    $rReason = !empty($it['reason']) ? $it['reason'] : $request->input('reason', 'Replacement');
                    $rNotes = isset($it['notes']) ? $it['notes'] : $request->input('notes');

                    $items[] = [
                        'product_id'             => $prodId,
                        'cases'                  => $c,
                        'bottles'                => $b,
                        'replacement_product_id' => $replId,
                        'replacement_cases'      => $rc,
                        'replacement_bottles'    => $rb,
                        'reason'                 => $rReason,
                        'notes'                  => $rNotes,
                        'image'                  => isset($it['image']) ? $it['image'] : null,
                    ];
                }
            }
        }

        // If no items in products array, check if flat single product fields are present
        if (empty($items) && $request->filled('product_id')) {
            $prodId = (int)$request->input('product_id');
            $c = (int)$request->input('cases', 0);
            $b = (int)$request->input('bottles', 0);
            $replId = (int)$request->input('replacement_product_id', $prodId);
            $rc = (int)$request->input('replacement_cases', $c);
            $rb = (int)$request->input('replacement_bottles', $b);

            $items[] = [
                'product_id'             => $prodId,
                'cases'                  => $c,
                'bottles'                => $b,
                'replacement_product_id' => $replId,
                'replacement_cases'      => $rc,
                'replacement_bottles'    => $rb,
                'reason'                 => $request->input('reason', 'Replacement'),
                'notes'                  => $request->input('notes'),
                'image'                  => null,
            ];
        }

        if (empty($items)) {
            return response()->json([
                'status' => 422,
                'message' => 'At least one product is required for replacement (provide product_id or products array).',
                'data' => []
            ], 422);
        }

        // Validate products existence and non-zero quantity
        foreach ($items as $item) {
            if (!DB::table('products')->where('id', $item['product_id'])->exists()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Product ID ' . $item['product_id'] . ' not found.',
                    'data' => []
                ], 422);
            }
            if (!empty($item['replacement_product_id']) && !DB::table('products')->where('id', $item['replacement_product_id'])->exists()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Replacement Product ID ' . $item['replacement_product_id'] . ' not found.',
                    'data' => []
                ], 422);
            }
            if ($item['cases'] <= 0 && $item['bottles'] <= 0) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Quantity (cases or bottles) must be greater than 0 for product ID ' . $item['product_id'] . '.',
                    'data' => []
                ], 422);
            }
        }

        // Image upload handling
        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = 'rep_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/replacements');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $imageName);
        } elseif ($request->hasFile('selfie')) {
            $file = $request->file('selfie');
            $imageName = 'rep_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/replacements');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $imageName);
        }

        DB::beginTransaction();
        try {
            $totalCases = 0;
            $totalBottles = 0;
            foreach ($items as $it) {
                $totalCases += (int)$it['cases'];
                $totalBottles += (int)$it['bottles'];
            }

            $firstItem = $items[0];

            // Generate unique replacement number
            $replacementNo = 'REP-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            while (ShopProductReplacement::where('replacement_no', $replacementNo)->exists()) {
                $replacementNo = 'REP-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }

            $replacement = ShopProductReplacement::create([
                'replacement_no'         => $replacementNo,
                'shop_id'                => (int)$shopId,
                'driver_id'              => (int)$driverId,
                'warehouse_id'           => $warehouseId ? (int)$warehouseId : null,
                'replace_date'           => $replaceDate,
                'total_products'         => count($items),
                'total_cases'            => $totalCases,
                'total_bottles'          => $totalBottles,
                'product_id'             => $firstItem['product_id'],
                'cases'                  => $firstItem['cases'],
                'bottles'                => $firstItem['bottles'],
                'replacement_product_id' => $firstItem['replacement_product_id'],
                'replacement_cases'      => $firstItem['replacement_cases'],
                'replacement_bottles'    => $firstItem['replacement_bottles'],
                'reason'                 => $request->input('reason', $firstItem['reason']),
                'status'                 => $request->input('status', 'pending'),
                'image'                  => $imageName,
                'notes'                  => $request->input('notes', $firstItem['notes']),
            ]);

            foreach ($items as $it) {
                $detailImage = !empty($it['image']) ? $it['image'] : $imageName;
                ShopProductReplacementDetails::create([
                    'replacement_id'         => $replacement->id,
                    'shop_id'                => (int)$shopId,
                    'driver_id'              => (int)$driverId,
                    'product_id'             => $it['product_id'],
                    'cases'                  => $it['cases'],
                    'bottles'                => $it['bottles'],
                    'replacement_product_id' => $it['replacement_product_id'],
                    'replacement_cases'      => $it['replacement_cases'],
                    'replacement_bottles'    => $it['replacement_bottles'],
                    'reason'                 => $it['reason'],
                    'status'                 => $request->input('status', 'pending'),
                    'image'                  => $detailImage,
                    'notes'                  => $it['notes'],
                ]);
            }

            DB::commit();

            // Load relations and format response
            $replacement->load([
                'shop:id,name,shop_name,mobile_no,address,city',
                'driver:id,name,email,phone',
                'warehouse:id,name,email,phone',
                'product:id,name,per_case_quantity,per_bottle_price',
                'replacementProduct:id,name,per_case_quantity,per_bottle_price',
                'details.product:id,name,per_case_quantity,per_bottle_price',
                'details.replacementProduct:id,name,per_case_quantity,per_bottle_price',
            ]);

            $replacement->image_url = $replacement->image ? url('uploads/replacements/' . $replacement->image) : null;

            return response()->json([
                'status' => 200,
                'message' => 'Shop product replacement recorded successfully.',
                'data' => $replacement,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'Error recording product replacement: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get product replacement records (history) filtered by driver, shop, date, etc.
     */
    public function getShopProductReplacements(Request $request)
    {
        // If a specific ID is requested
        $singleId = $request->input('replacement_id', $request->input('id'));
        if ($singleId) {
            $record = ShopProductReplacement::with([
                'shop:id,name,shop_name,mobile_no,address,city',
                'driver:id,name,email,phone',
                'warehouse:id,name,email,phone',
                'product:id,name,per_case_quantity,per_bottle_price',
                'replacementProduct:id,name,per_case_quantity,per_bottle_price',
                'details.product:id,name,per_case_quantity,per_bottle_price',
                'details.replacementProduct:id,name,per_case_quantity,per_bottle_price',
            ])->find($singleId);

            if (!$record) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Replacement record not found.',
                    'data' => null,
                ], 404);
            }

            $record->image_url = $record->image ? url('uploads/replacements/' . $record->image) : null;
            if ($record->details) {
                foreach ($record->details as $d) {
                    $d->image_url = $d->image ? url('uploads/replacements/' . $d->image) : null;
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Replacement record fetched successfully.',
                'data' => $record,
            ], 200);
        }

        $query = ShopProductReplacement::with([
            'shop:id,name,shop_name,mobile_no,address,city',
            'driver:id,name,email,phone',
            'warehouse:id,name,email,phone',
            'product:id,name,per_case_quantity,per_bottle_price',
            'replacementProduct:id,name,per_case_quantity,per_bottle_price',
            'details.product:id,name,per_case_quantity,per_bottle_price',
            'details.replacementProduct:id,name,per_case_quantity,per_bottle_price',
        ]);

        $driverId = $request->input('driver_id', $request->input('user_id'));
        if ($driverId) {
            $query->where('driver_id', $driverId);
        }

        $shopId = $request->input('shop_id', $request->input('customer_id'));
        if ($shopId) {
            $query->where('shop_id', $shopId);
        }

        $warehouseId = $request->input('warehouse_id');
        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }

        $replaceDate = $request->input('replace_date', $request->input('date'));
        if ($replaceDate) {
            $query->whereDate('replace_date', $replaceDate);
        }

        $status = $request->input('status');
        if ($status) {
            $query->where('status', $status);
        }

        $limit = (int)$request->input('limit', 50);
        $records = $query->orderBy('id', 'desc')->take($limit)->get();

        foreach ($records as $rec) {
            $rec->image_url = $rec->image ? url('uploads/replacements/' . $rec->image) : null;
            if ($rec->details) {
                foreach ($rec->details as $d) {
                    $d->image_url = $d->image ? url('uploads/replacements/' . $d->image) : null;
                }
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Shop product replacements fetched successfully.',
            'count' => count($records),
            'data' => $records,
        ], 200);
    }
}