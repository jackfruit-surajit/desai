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
use App\Models\SalesExecutiveArea;

class StaffController extends Controller {
    
    
    public function driverCreate(){
        $data = [];
        $data['vehicles'] = DB::table('vehicle')->where('status','1')->get();
        $data['areas'] = Area::where('status','1')->get();
        $data['warehouses'] = DB::table('admins as t1')->where('t1.role_id', '=',4)->orderby('t1.id','desc')->get();
        return view('admin.staff.driver_add', $data);
    }
    
    public function salesExecutiveCreate(){
        $data = [];
        $data['areas'] = Area::where('status','1')->get();
        return view('admin.staff.sales_executive_add', $data);
    }
    
    public function warehouseCreate(){
        $data = [];
        return view('admin.staff.warehouse_add', $data);
    }

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
            'area_id' => $request->role == 2 ? 'required|array|max:5' : 'required_if:role,3',
            'warehouse_id' => 'required_if:role,3',
        ], [
            'area_id.required_if' => 'The assign area field is required.',
            'area_id.required' => 'The assign area field is required.',
            'area_id.array' => 'The assign area must be a list.',
            'area_id.max' => 'You can assign maximum 5 areas.',
            'warehouse_id.required_if' => 'The assign warehouse field is required.',
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
               $data['area_id'] = $input['area_id'] ?? null; 
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

            if($input['role'] == '3'){
                if(isset($input['warehouse_id']) && $input['warehouse_id']){
                    DriverWarehouse::create([
                        'warehouse_id' => $input['warehouse_id'],
                        'driver_id' => $insertedId,
                    ]);
                }
                
                if($request->has('road_items') && is_array($request->road_items)) {
                    foreach($request->road_items as $index => $road_id) {
                        DriverRoute::create([
                            'driver_id' => $insertedId,
                            'road_id'   => $road_id,
                            'serial_no' => $index + 1,
                        ]);
                    }
                }
            } elseif ($input['role'] == '2') {
                if($request->has('area_id') && is_array($request->area_id)) {
                    foreach($request->area_id as $area_id) {
                        SalesExecutiveArea::create([
                            'sales_executive_id' => $insertedId,
                            'area_id' => $area_id,
                        ]);
                    }
                }
            }

            if($input['role'] == 3){
                return redirect()->route('drivers')->with('success_msg', 'Driver created successfully.');
            }
            elseif($input['role'] == 2){
                return redirect()->route('sales-executives')->with('success_msg', 'Sales Executive created successfully.');
            }
            else{
                return redirect()->route('warehouses')->with('success_msg', 'Warehouse created successfully.');
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error_msg', 'Something went wrong please check your input.');
        }
    }
    
    
    public function driverMonthlyAttendance($id, Request $request) {
        $driver_id = base64_decode($id);
        $driver = DB::table('admins')->where('id', $driver_id)->first();
        
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        $punch_ins = DB::table('driver_punch_in')
            ->where('user_id', $driver_id)
            ->whereMonth('punch_in_time', $month)
            ->whereYear('punch_in_time', $year)
            ->get();
            
        $attendance = [];
        $present_count = 0;
        $absent_count = 0;
        
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $record = $punch_ins->first(function ($val) use ($date) {
                return substr($val->punch_in_time, 0, 10) == $date;
            });
            $attendance[$date] = $record;
            
            if ($record) {
                $breakLogs = DB::table('user_break_logs')
                                ->where('user_id', $driver_id)
                                ->whereDate('break_start_time', $date)
                                ->get();
                $totalSeconds = 0;
                foreach($breakLogs as $log) {
                    if($log->break_start_time && $log->break_end_time) {
                        $start = strtotime($log->break_start_time);
                        $end = strtotime($log->break_end_time);
                        $totalSeconds += ($end - $start);
                    }
                }
                
                if($totalSeconds == 0) {
                    $record->total_break_time = '0 hrs 0 mins';
                } else {
                    $hours = floor($totalSeconds / 3600);
                    $minutes = floor(($totalSeconds / 60) % 60);
                    $record->total_break_time = $hours . ' hrs ' . $minutes . ' mins';
                }
                
                $present_count++;
            } else {
                if (strtotime($date) <= strtotime(date('Y-m-d'))) {
                    $absent_count++;
                }
            }
        }

        $data = [
            'driver' => $driver,
            'attendance' => $attendance,
            'current_month' => $month,
            'current_year' => $year,
            'present_count' => $present_count,
            'absent_count' => $absent_count,
        ];
        
        return view('admin.staff.driver_monthly_attendance', $data);
    }

    public function driverMonthlyRouteLog($id, Request $request) {
        $driver_id = base64_decode($id);
        $driver = DB::table('admins')->where('id', $driver_id)->first();
        
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $logs = DB::table('driver_attendance_route_logs as t1')
                ->leftJoin('road as t3', 't1.route_id', '=', 't3.id')
                ->select('t1.*', 't3.road_name as route_name')
                ->where('t1.driver_id', $driver_id)
                ->whereMonth('t1.created_at', $month)
                ->whereYear('t1.created_at', $year)
                ->orderBy('t1.created_at', 'desc')
                ->get();
                
        $data = [
            'driver' => $driver,
            'logs' => $logs,
            'current_month' => $month,
            'current_year' => $year,
        ];
        
        return view('admin.staff.driver_monthly_route_log', $data);
    }

    public function driverRouteLogs(Request $request) {
        $data = [];
        return view('admin.staff.driver_route_logs', $data);
    }

    public function getDriverRouteLogsDatatable(Request $request) {
        $query = DB::table('driver_attendance_route_logs as t1')
                ->leftJoin('admins as t2', 't1.driver_id', '=', 't2.id')
                ->leftJoin('road as t3', 't1.route_id', '=', 't3.id')
                ->select('t1.*', 't2.name as driver_name', 't3.road_name as route_name');

        if ($request->has('filter_date') && !empty($request->filter_date)) {
            $query->whereDate('t1.created_at', $request->filter_date);
        }

        $data = $query->orderBy('t1.id', 'desc')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($model) {
                return $model->created_at ? date('d-m-Y H:i:s', strtotime($model->created_at)) : 'N/A';
            })
            ->make(true);
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
                if(!$model->punch_in_time) return 'N/A';
                $punchInDate = date('Y-m-d', strtotime($model->punch_in_time));
                $breakLogs = DB::table('user_break_logs')
                                ->where('user_id', $model->user_id)
                                ->whereDate('break_start_time', $punchInDate)
                                ->get();
                $totalSeconds = 0;
                foreach($breakLogs as $log) {
                    if($log->break_start_time && $log->break_end_time) {
                        $start = strtotime($log->break_start_time);
                        $end = strtotime($log->break_end_time);
                        $totalSeconds += ($end - $start);
                    }
                }
                
                if($totalSeconds == 0) return '0 hrs 0 mins';
                
                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds / 60) % 60);
                return $hours . ' hrs ' . $minutes . ' mins';
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
        
        $data = DB::table('admins as t1')
                ->leftjoin('roles as t2','t1.role_id','t2.id')
                ->leftjoin('vehicle as t3','t1.vehicle_id','t3.id')
                ->select('t1.*','t2.name as role_name', 't3.vehicle_name', 't3.vehicle_no')
                ->where('t1.status', '<>','2')->where('t2.id', '=',3)
                ->orderby('t1.id','desc')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            
            ->addColumn('vehicle', function ($model) {
                return $model->vehicle_name ? ($model->vehicle_name . ' - ' . $model->vehicle_no) : 'N/A';
            })

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
                $edit = ''; $delete = $assign_route = $ware_house = $attendance_log = $route_log = '';
                
                $edit = '<a href="' . Route("driver-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary"><i class="fa fa-edit"></i> Edit</span></a>';
                $delete = '<a href="javascript:;" onclick="deleteStaff(this);" data-href="' . Route("staff-delete", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash"></i> Delete</span></a>';
                // $assign_route = '<a href="' . Route("driver-assign-location", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-success"><i class="fa fa-route"></i> Routes</span></a>';
                // $ware_house = '<a href="' . Route("driver-warehouse", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-secondary"><i class="fa fa-house"></i> Warehouse</span></a>';
                $attendance_log = '<a href="' . Route("driver-monthly-attendance", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-info"><i class="fa fa-clock"></i> Attendance Log</span></a>';
                $route_log = '<a href="' . Route("driver-monthly-route-log", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-warning"><i class="fa fa-map-marker-alt"></i> Route Log</span></a>';

                return
                    '<div class="action-btns">'.
                        $assign_route.$edit .
                        $delete. $ware_house. $attendance_log . $route_log .
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
    
    
    public function getRoadsByArea(Request $request) {
        $area_id = $request->area_id;
        $roads = Road::where('status','1')->where('area_id', $area_id)->get();
        return response()->json([
            'status' => true,
            'data' => $roads
        ]);
    }
    
    public function driverEdit($id) {
        $id = base64_decode($id);
        $model = DB::table('admins')->where('id', $id)->first();
        if (!$model) {
            return redirect()->back()->with('error_msg', 'Invalid Link!');
        }
        $data['id'] = $id;
        $data['model'] = $model;
        $data['vehicles'] = DB::table('vehicle')->where('status','1')->get();
        $data['areas'] = Area::where('status','1')->get();
        $data['warehouses'] = DB::table('admins as t1')->where('t1.role_id', '=',4)->orderby('t1.id','desc')->get();
        $data['selected_warehouse'] = DB::table('driver_warehouse')->where('driver_id', $id)->value('warehouse_id');
        
        $data['driver_routes'] = DriverRoute::where('driver_id', $id)
                                    ->join('road', 'road.id', '=', 'driver_route.road_id')
                                    ->orderBy('driver_route.serial_no', 'ASC')
                                    ->select('road.id', 'road.road_name', 'road.full_address', 'driver_route.serial_no')
                                    ->get();

        return view('admin.staff.driver_edit', $data);
    }

    public function salesExecutiveEdit($id) {
        $id = base64_decode($id);
        $model = DB::table('admins')->where('id', $id)->first();
        if (!$model) {
            return redirect()->back()->with('error_msg', 'Invalid Link!');
        }
        $data['id'] = $id;
        $data['model'] = $model;
        $data['areas'] = Area::where('status','1')->get();
        $data['selected_areas'] = SalesExecutiveArea::where('sales_executive_id', $id)->pluck('area_id')->toArray();
        return view('admin.staff.sales_executive_edit', $data);
    }

    public function warehouseEdit($id) {
        $id = base64_decode($id);
        $model = DB::table('admins')->where('id', $id)->first();
        if (!$model) {
            return redirect()->back()->with('error_msg', 'Invalid Link!');
        }
        $data['id'] = $id;
        $data['model'] = $model;
        return view('admin.staff.warehouse_edit', $data);
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
            'area_id' => $request->role == 2 ? 'required|array|max:5' : 'required_if:role,3',
            'warehouse_id' => 'required_if:role,3',
        ], [
            'area_id.required_if' => 'The assign area field is required.',
            'area_id.required' => 'The assign area field is required.',
            'area_id.array' => 'The assign area must be a list.',
            'area_id.max' => 'You can assign maximum 5 areas.',
            'warehouse_id.required_if' => 'The assign warehouse field is required.',
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
            
            if($input['role'] == '3'){
               if (isset($input['vehicle_id'])) {
                   $data['vehicle_id'] = $input['vehicle_id']; 
               }
               $data['area_id'] = $input['area_id'] ?? null;
               
               if(isset($input['warehouse_id']) && $input['warehouse_id']){
                    DriverWarehouse::updateOrCreate(
                        ['driver_id' => $request->id],
                        ['warehouse_id' => $input['warehouse_id']]
                    );
               }
               
               if($request->has('road_items') && is_array($request->road_items)) {
                    DriverRoute::where('driver_id', $request->id)->delete();
                    foreach($request->road_items as $index => $road_id) {
                        DriverRoute::create([
                            'driver_id' => $request->id,
                            'road_id' => $road_id,
                            'serial_no' => $index + 1
                        ]);
                    }
                } elseif($request->has('area_id')) {
                    // If no road_items are provided but area_id is, user might have cleared the routes for this area
                    DriverRoute::where('driver_id', $request->id)->delete();
                }
            } elseif ($input['role'] == '2') {
                SalesExecutiveArea::where('sales_executive_id', $request->id)->delete();
                if($request->has('area_id') && is_array($request->area_id)) {
                    foreach($request->area_id as $area_id) {
                        SalesExecutiveArea::create([
                            'sales_executive_id' => $request->id,
                            'area_id' => $area_id,
                        ]);
                    }
                }
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
                return redirect()->route('warehouses')->with('success_msg', 'Warehouse User details updated successfully.');
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