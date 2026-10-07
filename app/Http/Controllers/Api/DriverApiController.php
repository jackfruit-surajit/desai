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
use Illuminate\Support\Facades\Validator;
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
        
        $result = DB::table('admins')->where('id',$request->user_id)->first(['id','role_id','name','email','phone','image']);

        if($result){
            $result->image = url('public/uploads/staff/'.$result->image);
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
    
            if($result){
                return response()->json(['status' => 200,'message' => 'Break time updated successfully.', 'data'  =>[]],200);
            }else{
                return response()->json(['status' => 500,'message' => 'unauthorized error.', 'data'  =>[]],500);
            }
        }else{
            return response()->json(['status' => 200,'message' => 'Please Punch in your daliy profile.', 'data'  =>[]],200);
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
            DriverAttendanceRouteLog::create(['driver_id' => $request->user_id,'route_id' => $request->route_id]);
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
            
            return response()->json(['status' => 200,'message' => 'Record found.', 'image_url' => url('public/uploads/customer/'), 'data'  =>$result,],200);
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
                'driver_id'        => 'required|integer|exists:admins,id',
            ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        if($driver_atten = DriverPunchIn::where('user_id',$request->driver_id)->whereDate('created_at',date('Y-m-d'))->first()){
            return response()->json(['status' => 200,'message' => 'Record found.', 'data'  =>$driver_atten,],200);   
        }
        else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'data'  =>[],],200);
        }
        
        
    }
    
    
    
}