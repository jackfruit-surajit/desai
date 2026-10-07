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
use App\Models\SalesExecutiveArea;


class SalesExecutiveController extends Controller {
    
    public function salesExecutives(Request $request) {
        $data = [];
        $data['roles'] = DB::table('roles')->get();
        
        return view('admin.staff.sales_executive', $data);
    }
    
    public function getSalesExecutiveDatatable(Request $request) {
        
        
        $data = DB::table('admins as t1')->leftjoin('roles as t2','t1.role_id','t2.id')
                ->select('t1.*','t2.name as role_name')
                ->where('t1.status', '<>','2')->where('t2.id', '=',2)
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
                $user_role = Auth::guard('backend')->user()->role_id;
                $edit = ''; $delete = '';
                if($user_role == 1){
                $edit = '<a href="' . Route("staff-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary"><i class="fa fa-edit"></i> Edit</span></a>';
                $delete = '<a href="javascript:;" onclick="deleteStaff(this);" data-href="' . Route("staff-delete", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash"></i> Delete</span></a>';
                $assign_route = '<a href="' . Route("sales-executive-assign-location", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-success"><i class="fa fa-route"></i> Area</span></a>';
                $attendance_log = '<a href="' . Route("driver-monthly-attendance", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-info"><i class="fa fa-clock"></i> Attendance Log</span></a>';
                $area_log = '<a href="' . Route("sales-executive-area-log", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-warning"><i class="fa fa-map-marker-alt"></i> Area Log</span></a>';
                }
                return
                    '<div class="action-btns">'.
                        $edit .
                        $delete.$assign_route.$attendance_log.$area_log.
                    '</div>';
            })
            ->rawColumns(['image','created_at','status', 'action'])
            ->make(true);
    }
    
    public function salesExecutiveAssignLoaction(Request $request,$id){
        $user_id = base64_decode($id);
        $model = DB::table('admins')->where('id', $user_id)->first();
        $selected_areas = SalesExecutiveArea::where('sales_executive_id',$user_id)->get(['area_id']);
        $areas = Area::where('status','1')->get();
        return view('admin.staff.sales_executive_assign_location',compact(['areas','model','selected_areas']));
    }
    
    public function salesExecutiveAreaUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|array|max:5',
            'area_id.*' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
        
        if ($validator->passes()) {
            
            $check_area = SalesExecutiveArea::where('sales_executive_id',$request->user_id)->get();
            if(count($check_area) > 0){
                SalesExecutiveArea::where('sales_executive_id',$request->user_id)->delete();
            }
            
            foreach($request->area_id as $area_id){
                
                SalesExecutiveArea::create([
                    'sales_executive_id' => $request->user_id,
                    'area_id' => $area_id,
                ]);
            }
            
            return redirect()->route('sales-executives')->with('success_msg', 'Area updated successfully.');
        } 
        else {
            return redirect()->back()->withErrors($validator)->withInput()->with('error_msg', 'Something went wrong please check your input.');
        }
    }
    
    public function salesExecutiveAreaLog($id, Request $request) {
        $executive_id = base64_decode($id);
        $executive = DB::table('admins')->where('id', $executive_id)->first();
        
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $logs = DB::table('sales_executive_area_visit_logs as t1')
                ->leftJoin('area as t2', 't1.area_id', '=', 't2.id')
                ->select('t1.*', 't2.area_name')
                ->where('t1.sales_executive_id', $executive_id)
                ->whereMonth('t1.created_at', $month)
                ->whereYear('t1.created_at', $year)
                ->orderBy('t1.created_at', 'desc')
                ->get();
                
        $data = [
            'executive' => $executive,
            'logs' => $logs,
            'current_month' => $month,
            'current_year' => $year,
        ];
        
        return view('admin.staff.sales_executive_area_log', $data);
    }
    
}