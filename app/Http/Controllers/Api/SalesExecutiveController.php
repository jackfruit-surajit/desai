<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Road;
use App\Models\Area;
use App\Models\DriverRoute;
use App\Models\Customer;
use App\Models\DriverPunchIn;
use App\Models\ExecutiveVisitHistory;
use App\Models\SalesExecutiveArea;
use App\Models\SalesExecutiveRoute;
use App\Models\SalesExecutiveAreaVisitLog;
use Illuminate\Support\Facades\Validator;
use Hash;
use DB;

class SalesExecutiveController extends Controller
{
    public function routeList(Request $request){
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'area_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
            
        $result = Road::where('area_id',$request->area_id)->get(['road.id as route_id','road.road_name','road.full_address']);
        
        if($check_log = SalesExecutiveAreaVisitLog::where(['sales_executive_id' => $request->user_id, 'area_id' => $request->area_id])->whereDate('created_at',date('Y-m-d'))->first()){
            SalesExecutiveAreaVisitLog::where('id',$check_log->id)->update(['sales_executive_id' => $request->user_id, 'area_id' => $request->area_id]);
        }else{
            SalesExecutiveAreaVisitLog::create(['sales_executive_id' => $request->user_id, 'area_id' => $request->area_id]);
        }

        if($result){
            $res = array();
            foreach($result as $val){
                $count_shop = Customer::where('created_by',$request->user_id)->where('road_id',$val->route_id)->count();
                $val->total_shop = $count_shop;
                
                $res[] = $val;
            }
            
            return response()->json(['status' => 200,'message' => 'Record found.', 'total_route' => count($result), 'data'  =>$res],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'data'  =>[]],200);
        }
    }
    
    public function addShop(Request $request){
        
        $validator = Validator::make($request->all(), [
            'user_id' =>'required|integer',
            'route_id' => 'required|integer',
            'shop_name' => 'required|string|unique:customers,shop_name',
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
            'previous_shop_id' =>'nullable|integer',
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
        
        $road = Road::where('id',$request->route_id)->first(['road_name','area_id']);
        $area = Area::where('id',$road->area_id)->first(['area_name']);
            
        $data = array(
                'shop_name' => $request->shop_name,
                'road_id' => $request->route_id,
                'name' => $request->owner_name,
                'address' => $road->road_name.', '.$area->area_name.', '.$request->landmark.', '.$request->state.', '.$request->city.', '.$request->pin_code,
                'mobile_no' => $request->mobile_no,
                'created_by' => $request->user_id,
                
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
            return response()->json(['status' => 200,'message' => 'Shop added successfully.', 'data'  =>$result,],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Error!! while adding shop.', 'data' => [],],200);
        }
    }
    
    public function getSingleShop(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'shop_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        $shop = Customer::where('created_by',$request->user_id)->where('id',$request->shop_id)->first(['id','shop_name','address','name as owner_name','mobile_no','gst_no','pan_no','shop_photo','latitude','longitude']);
        
        if($shop){
            
            if($check_status = ExecutiveVisitHistory::where('shop_id',$request->shop_id)->where('executive_id',$request->user_id)->whereDate('created_at',date('Y-m-d'))->first()){
                    
                if($check_status->status == '1'){
                        $status = 'Visited';
                }
                elseif($check_status->status == '2'){
                        $status = 'Skipped';
                }
            }
            else{
                    $status = 'Pending';
            }
            
             $shop->status = $status;
             $shop->image_path = url('public/uploads/customer/'.$shop->shop_photo);
             return response()->json(['status' => 200,'message' => 'Record found.', 'data'  =>$shop],200);
        }
        else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'data'  =>[]],200);
        }
    }
    
    public function shopList(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'route_id' => 'nullable|integer',
            'keyword'  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
            $shops = Customer::where('created_by',$request->user_id);
        
        if($request->route_id){
            $shops = $shops->where('road_id',$request->route_id);
        }
        
        if($request->keyword){
            $shops = $shops->whereLike('shop_name', '%'.$request->keyword.'%');
        }
        
            $shops = $shops->get(['id','shop_name','address','name as owner_name','mobile_no']);
        
        if(count($shops) > 0){
            $item = array();
            foreach($shops as $shop){
                
                if($check_status = ExecutiveVisitHistory::where('shop_id',$shop->id)->where('executive_id',$request->user_id)->whereDate('created_at',date('Y-m-d'))->first()){
                    
                    if($check_status->status == '1'){
                        $status = 'Visited';
                    }
                    elseif($check_status->status == '2'){
                        $status = 'Skipped';
                    }
                }else{
                    $status = 'Pending';
                }
                
                $shop->status = $status;
                $item[] = $shop;
            }
            
            return response()->json(['status' => 200,'message' => 'Record found.', 'total_shop' => count($shops), 'data'  =>$item],200);
        }
        else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'data'  =>[]],200);
        }
        
    }
    
    public function shopVisit(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'shop_id'  => 'required|string',
            'shop_photo'  => 'nullable|file|image',
            'customer_note'  => 'nullable|string',
            'shop_requirement'  => 'nullable|string',
            'skip_reason' => 'nullable|string',
            'status'  => 'required|string', // 0 :pending, 1: visited, 2:skip
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        
            $data = array(
                'shop_id' => $request->shop_id,
                'executive_id' => $request->user_id,
                'status' => $request->status,
                'customer_note' => $request->customer_note,
                'shop_requirement' => $request->shop_requirement,
                'skip_reason' => $request->skip_reason,
             );
             
        
            if ($request->hasFile('shop_photo')) {
                $sample_image = $request->file('shop_photo');
                $imagename = $this->rand_string(14) . '.' . $sample_image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/sales_executive');
                $sample_image->move($destinationPath, $imagename);
                $data['shop_photo'] = $imagename;
            }
        
            if(ExecutiveVisitHistory::where('shop_id',$request->shop_id)->where('executive_id',$request->user_id)->whereDate('created_at',date('Y-m-d'))->first()){
                $res = ExecutiveVisitHistory::where('shop_id',$request->shop_id)->where('executive_id',$request->user_id)->whereDate('created_at',date('Y-m-d'))->update($data);
            }
            else{
                $res = ExecutiveVisitHistory::create($data);
            }
        
        if($res){
            return response()->json(['status' => 200,'message' => 'Visit Completed.','data'  =>$res],200);
        }
        else{
            return response()->json(['status' => 200,'message' => 'Unauthorize Error.', 'data'  =>[]],200);
        }
        
    }
    
    public function salesExecutivePunchIn(Request $request){
        
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
    
    public function salesExecutivePunchOut(Request $request){
        
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
    
    public function salesExecutiveBreak(Request $request){
        
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
    
    public function salesExecutiveArea(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }
        
        $areas = SalesExecutiveArea::join('area','sales_executive_area.area_id','area.id')
                ->where('sales_executive_area.sales_executive_id',$request->user_id)->get(['area.id','area.area_name']);
        
        if(count($areas) > 0){
            return response()->json(['status' => 200,'message' => 'Record found.', 'total_area' => count($areas), 'data'  =>$areas],200);
        }
        else{
            return response()->json(['status' => 200,'message' => 'Record not found.', 'data'  =>[]],200);
        }
        
    }
    
}