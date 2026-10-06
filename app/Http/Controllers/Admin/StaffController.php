<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Mail;
use Auth;
use Validator;
use Hash;
use URL;
use DB;
use PDF;
use Session;
use DateTime;
use Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\Area;
use App\Models\Road;
use App\Models\DriverRoute;
use App\Models\DriverWarehouse;

class StaffController extends Controller {
    
    
    public function create(){
        $data = [];
        $data['roles'] = DB::table('roles')->whereNotIn('name',['Admin'])->get();
        $data['vehicles'] = DB::table('vehicle')->where('status','1')->get();
        return view('admin.staff.add', $data);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required',
            'phone' => 'required|numeric|digits:10',
            'image' => 'nullable|mimes:png,jpeg,jpg,JPEG',
            'status' => 'required',
            'vehicle_id' =>'nullable|integer',
        ]);

        $validator->after(function ($validator) use ($request) {

            $checkUserEmail = DB::table('admins')->where('email', $request->input('email'))->where('type_id','2')->count();
            if ($checkUserEmail > 0) {
                $validator->errors()->add('email', 'Email already in use.');
            }

            $checkUserPhone = DB::table('admins')->where('phone', $request->input('phone'))->where('type_id','2')->count();
            if ($checkUserPhone > 0) {
                $validator->errors()->add('phone', 'Phone number already in use.');
            }
            

        });

        if ($validator->passes()) {

            $input = [];
            $data = [];
            $input = $request->all();

            unset($input['_token']);

            $data['name'] = $input['name'];
            $data['email'] = $input['email'];
            $data['phone'] = $input['phone'];
            $data['role_id'] = $input['role'];
            $data['status'] = $input['status'];
            $data['type_id'] = '2';
           
            if($input['role'] == '3'){
               $data['vehicle_id'] = $input['vehicle_id']; 
            }
            
            if ($request->hasFile('image')) {
                $sample_image = $request->file('image');
                $imagename = $this->rand_string(14) . '.' . $sample_image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/staff');
                $sample_image->move($destinationPath, $imagename);
                $data['image'] = $imagename;
            }

            $password = $input['password'];
            $hashPass = Hash::make($password);
            $data['password'] = $hashPass;


            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');

            $insertedId = DB::table('admins')->insertGetId($data);

            return redirect()->back()->with('success_msg', 'Staff created successfully.');
        } else {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error_msg', 'Something went wrong please check your input.');
        }
    }
    
    
    public function driverAttendance(Request $request) {
        $data = [];
        return view('admin.staff.driver_attendance', $data);
    }

    public function getDriverAttendanceDatatable(Request $request) {
        $query = DB::table('driver_punch_in as t1')
                ->leftJoin('admins as t2', 't1.user_id', '=', 't2.id')
                ->select('t1.*', 't2.name as driver_name')
                ->where('t2.role_id','3');
                
        if ($request->has('filter_date') && !empty($request->filter_date)) {
            $query->whereDate('t1.punch_in_time', $request->filter_date);
        }

        $data = $query->orderBy('t1.id', 'desc')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('selfie', function ($model) {
                if (isset($model->selfie) && $model->selfie != '') {
                    $path = URL::asset('public/uploads/driver/' . $model->selfie);
                    return '<img height="50" width="50" src="' . $path . '"/>';
                } else {
                    return 'N/A';
                }
            })
            ->editColumn('punch_in_time', function ($model) {
                return $model->punch_in_time ? date('d-m-Y H:i:s', strtotime($model->punch_in_time)) : 'N/A';
            })
            ->editColumn('break_time', function ($model) {
                return $model->break_time ? date('d-m-Y H:i:s', strtotime($model->break_time)) : 'N/A';
            })
            ->editColumn('punch_out_time', function ($model) {
                return $model->punch_out_time ? date('d-m-Y H:i:s', strtotime($model->punch_out_time)) : 'N/A';
            })
            ->rawColumns(['selfie'])
            ->make(true);
    }

    public function driverList(Request $request) {
        $data = [];
        $data['roles'] = DB::table('roles')->get();
        
        return view('admin.staff.driver_list', $data);
    }

    public function getDriverDatatable(Request $request) {
        
        
        $data = DB::table('admins as t1')->leftjoin('roles as t2','t1.role_id','t2.id')
                ->select('t1.*','t2.name as role_name')
                ->where('t1.status', '<>','2')->where('t2.id', '=',3)
                ->orderby('t1.id','desc')->get();

        return Datatables::of($data)
            ->addIndexColumn()

            ->editColumn('image', function ($model) {
                if (isset($model->image) && $model->image != '') {
                    $path = URL::asset('public/uploads/staff/' . $model->image);
                } else {
                    $path = URL::asset('public/common/image/no-img.png');
                }
                return '<img height="50" width="50" src="' . $path . '"/>';
            })
            
            ->editColumn('role', function ($model) {
                return $model->role_name;
            })
            ->editColumn('created_at', function ($model) {
                return !empty($model->created_at) ? date('d-m-Y', strtotime($model->created_at)) : '';
            })
            ->editColumn('status', function ($model) {
                
                $user_role = Auth::guard('backend')->user()->role_id;
                
                if ($model->status == '0') {
                    $status = '';
                } else if ($model->status == '1') {
                    $status = 'checked';
                } else if ($model->status == '2') {
                    $status = '';
                }
                
                if($user_role == 1){
                     $status = '<div class="container">
                                <div class="row text-center">
                                    <div class="col-md-12 offset-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                            type="checkbox" 
                                            role="switch" 
                                            id="flexSwitchCheckChecked_'.$model->id.'" '.$status.' 
                                            data-href="' . Route("staff-status-change", ['id' => base64_encode($model->id)]) . '"
                                            onchange="changeStatusStaff(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    
                }else{
                    
                    if ($model->status == '0') {
                        $res = 'Inactive';
                    } else if ($model->status == '1') {
                        $res = 'Active';
                    } else if ($model->status == '2') {
                        $res = 'Deleted';
                     }
                    
                     $status = '<span class="badge bg-primary">'.$res.'</span>';
                }
                
                return $status;
            })
            ->addColumn('action', function ($model) {
                $edit = ''; $delete = $assign_route = $ware_house ='';
                
                $edit = '<a href="' . Route("staff-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary"><i class="fa fa-edit"></i> Edit</span></a>';
                $delete = '<a href="javascript:;" onclick="deleteStaff(this);" data-href="' . Route("staff-delete", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash"></i> Delete</span></a>';
                $assign_route = '<a href="' . Route("driver-assign-location", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-success"><i class="fa fa-route"></i> Routes</span></a>';
                $ware_house = '<a href="' . Route("driver-warehouse", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-secondary"><i class="fa fa-house"></i> Warehouse</span></a>';

                return
                    '<div class="action-btns">'.
                        $assign_route.$edit .
                        $delete. $ware_house.
                    '</div>';
            })
            ->rawColumns(['image','created_at','status', 'action'])
            ->make(true);
    }

    public function driverAssignLoaction(Request $request,$id){
        $user_id = base64_decode($id);
        $model = DB::table('admins')->where('id', $user_id)->first();
        $areas = Area::where('status','1')->get();
        $roads = Road::where('status','1')->where('area_id',$model->area_id)->get();
        
        return view('admin.staff.driver_assign_location',compact(['areas','roads','model']));
    }
    
    public function driverAreaUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
        
        if ($validator->passes()) {
             DB::table('admins')->where('id',$request->user_id)->update(['area_id' => $request->area_id]);
             return redirect()->back()->with('success_msg', 'Area updated successfully.');
        } 
        else {
            return redirect()->back()->withErrors($validator)->withInput()->with('error_msg', 'Something went wrong please check your input.');
        }
    }
    
    public function driverRoadArrangement(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.sort_order' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        DB::transaction(function () use ($request) {
            
            DriverRoute::where('driver_id',$request->user_id)->delete();
            foreach ($request->items as $item) {

                DriverRoute::create([
                        'driver_id' => $request->user_id,
                        'road_id'   => $item['id'],
                        'serial_no' => $item['sort_order'],
                    ]);
            }
        });

        return response()->json([
            'status' => true,
            'message' => 'Order updated successfully.'
        ]);
    }
    
    
    public function edit($id) {
        $id = base64_decode($id);
        $model = DB::table('admins')->where('id', $id)->first();
        if (!$model) {
            return redirect()->back()->with('error_msg', 'Invalid Link!');
        }
        $data['id'] = $id;
        $data['model'] = $model;
        $data['vehicles'] = DB::table('vehicle')->where('status','1')->get();
        $data['roles'] = DB::table('roles')->whereNotIn('name',['Admin'])->get();
        return view('admin.staff.edit', $data);
    }

    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email',
            'role' => 'required',
            'password' => 'nullable|string',
            'phone' => 'required|numeric|digits:10',
            'image' => 'nullable|mimes:png,jpeg,jpg,JPEG',
            'status' => 'required',
            'id' => 'required|integer',
            'vehicle_id' => 'nullable|integer',
        ]);

        $validator->after(function ($validator) use ($request) {

            $checkUserEmail = DB::table('admins')->where('id', '<>', $request->input('id'))->where('email', $request->input('email'))->where('type_id','2')->first();
            if (!empty($checkUserEmail)){
                $validator->errors()->add('email', 'Email already in use.');
            }

            $checkUserPhone = DB::table('admins')->where('id', '<>', $request->input('id'))->where('phone', $request->input('phone'))->where('type_id','2')->count();
            if ($checkUserPhone > 0) {
                $validator->errors()->add('phone', 'Phone number already in use.');
            }

        });

        if ($validator->passes()) {

            $input = [];
            $data = [];
            $input = $request->all();

            unset($input['_token']);
            $data['name'] = $input['name'];
            $data['email'] = $input['email'];
            $data['phone'] = $input['phone'];
            $data['role_id'] = $input['role'];
            $data['status'] = $input['status'];
            
            if($input['role'] == '3' && $input['vehicle_id']){
               $data['vehicle_id'] = $input['vehicle_id']; 
            }

            if ($request->hasFile('image')) {
                $sample_image = $request->file('image');
                $imagename = $this->rand_string(14) . '.' . $sample_image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/staff');
                $sample_image->move($destinationPath, $imagename);
                $data['image'] = $imagename;

                $exist = DB::table('admins')->select('image')->where('id', $request->id)->first();
                if (!empty($exist)){
                    if (isset($exist->image)){
                        if (file_exists(public_path('uploads/staff/' . $exist->image))) {
                            unlink(public_path('uploads/staff/' . $exist->image));
                        }
                    }
                }

            }
            if($request->input('password')){
                $password = $input['password'];
                $hashPass = Hash::make($password);
                $data['password'] = $hashPass;
            }
            
            $data['updated_at'] = date('Y-m-d H:i:s');
            $model = DB::table('admins')->where('id',$request->id)->update($data);
            
            if($data['role_id'] == 3){
                return redirect()->route('drivers')->with('success_msg', 'Driver details updated successfully.');
            }
            elseif($data['role_id'] == 2){
                return redirect()->route('sales-executives')->with('success_msg', 'Sales Executive details updated successfully.');
            }
            else{
                return redirect()->route('warehouses')->with('success_msg', 'Sales Executive details updated successfully.');
            }
            
        } else {
            return redirect()->back()->withErrors($validator)->withInput()->with('error_msg', 'Something went wrong please check your input.');
        }
    }

    public function delete($id) {
        $data = [];
        $input = [];
        $id = base64_decode($id);

        $model = DB::table('admins')->select('image','status')->where('id', $id)->first();
        
        if (!empty($model)) {

            $input['image'] = NULL;
            $input['status'] = '2';
            $input['updated_at'] = date('Y-m-d H:i:s');

            DB::table('admins')->where('id',$id)->update($input);
            // DB::table('admins')->where('id',$id)->delete();

            $data['status'] = 200;
            $data['msg'] = 'Staff deleted successfully.';
        } else {
            $data['msg'] = 'Sorry ! No Staff details found.';
        }
        return response()->json($data);
        //--- Redirect Section Ends     
    }
    
    public function statusChange(Request $request,$id){
        $datareturn = [];
        $data = [];
        $input = [];
        $id = base64_decode($id);
        $input = $request->all();

        $data['status'] = $input['status'];
        $data['updated_at'] = date('Y-m-d H:i:s');

        $model = DB::table('admins')->where('id',$id)->update($data);
        $datareturn['status'] = 200;
        $datareturn['msg'] = 'Staff status updated successfully.';
        return response()->json($datareturn);
    }
    
    public function driverWarehouse(Request $request,$id) {
        $id = base64_decode($id);
        $model = DB::table('admins')->where('id', $id)->first();
        $warehouses = DB::table('admins as t1')->where('t1.role_id', '=',4)->orderby('t1.id','desc')->get();
        $select_warehouse = DriverWarehouse::where('driver_id',$model->id)->first();
        return view('admin.staff.driver_warehouse', compact(['warehouses','model','select_warehouse']));
    }
    
    public function updateDriverWarehouse(Request $request){
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|integer',
            'warehouse' => 'required|integer',
        ]);

        $validator->after(function ($validator) use ($request) {

        });

        if ($validator->passes()) {
            
            if(DriverWarehouse::where('driver_id',$request->driver_id)->first()){
                DriverWarehouse::where('driver_id',$request->driver_id)->delete();
            }
            
            DriverWarehouse::create([
                'warehouse_id' => $request->warehouse,
                'driver_id' => $request->driver_id,
            ]);
            
            return redirect()->route('drivers')->with('success_msg', 'Warehouse added successfully.');
        }
        else {
            return redirect()->back()->withErrors($validator)->withInput()->with('error_msg', 'Something went wrong please check your input.');
        }
    }
    
    

}