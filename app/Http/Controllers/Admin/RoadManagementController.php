<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Road;
use App\Models\Area;
use DB;
use URL;
use Auth;
use Validator;
use Hash;

class RoadManagementController extends Controller
{
    
    public function index(Request $request){
        return view('admin.road.list');
    }

    public function getRoadDatatable(Request $request) {
        $data = DB::table('road')->join('area','road.area_id','area.id')
                ->orderBy('road.id','desc')->get(['road.*','area.area_name']);

        return Datatables::of($data)
            ->addIndexColumn()

            ->editColumn('status', function ($model) {                
                if ($model->status == '0') {
                    $status = '';
                } else if ($model->status == '1') {
                    $status = 'checked';
                } else if ($model->status == '2') {
                    $status = '';
                }
                $status = '<div class="container">
                                <div class="row text-center">
                                    <div class="col-md-12 offset-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                            type="checkbox" 
                                            role="switch" 
                                            id="flexSwitchCheckChecked_'.$model->id.'" '.$status.' 
                                            data-href="' . Route("road-status-change", ['id' => base64_encode($model->id)]) . '"
                                            onchange="changeStatusStaff(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    
                return $status;
            })

            ->addColumn('action', function ($model) {

                $edit = $delete ='';

                $edit = '<a href="' . Route("road-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary" title="Edit"><i class="fa fa-edit"></i></span></a>';    
                $delete = '<a href="' . Route("road-delete", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash" title="Delete"></i></span></a>';

                return
                    '<div class="action-btns">'.
                        $edit . $delete.
                    '</div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create(Request $request){
        $areas = Area::where('status','1')->orderBy('id','desc')->get();
        return view('admin.road.add',compact(['areas']));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|integer',
            'road_name' => 'required|string',
            // 'full_address' => 'nullable|string',
            'status' => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {

        });

        if ($validator->passes()) {
            $data = array(
                'area_id'      => $request->area_id,
                'road_name'    => $request->road_name,
                // 'full_address' => $request->full_address,
                'status'       => $request->status,
            );

            Road::create($data);
            return redirect()->route('roads')->with('success_msg', 'Road details added successfully.');
        } else {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error_msg', 'Something went wrong please check your input.');
        }
    }
    
    public function statusChange(Request $request,$id){
        $datareturn = [];
        $data = [];
        $input = [];
        $id = base64_decode($id);
        $input = $request->all();

        $data['status'] = $input['status'];
        $data['updated_at'] = date('Y-m-d H:i:s');

        $model = DB::table('road')->where('id',$id)->update($data);
        $datareturn['status'] = 200;
        $datareturn['msg'] = 'Road status updated successfully.';
        return response()->json($datareturn);
    }

    public function delete($id) {
        $id = base64_decode($id);
        $model = DB::table('road')->where('id', $id)->first();
        
        if (!empty($model)) {

            if(DB::table('road')->where('id',$id)->delete()){
                return redirect()->route('roads')->withSuccess('Road deleted successfully.');
            }
            else{
                return redirect()->back()->withErrors('Error!! while deleting road!!!'); 
            }
        } else {
            return redirect()->back()->withErrors('Sorry ! No road details found.'); 
        }
    }
      
    public function edit($id) {
        $id = base64_decode($id);
        $model = Road::where('id', $id)->first();
        if (!$model) {
            return redirect()->back()->with('error_msg', 'Invalid Link!');
        }
        $data['id'] = $id;
        $data['model'] = $model;
        $data['areas'] = Area::where('status','1')->orderBy('id','desc')->get();
        return view('admin.road.edit', $data);
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|integer',
            'road_name' => 'required|string',
            // 'full_address' => 'nullable|string',
            'status' => 'required',
            'id' => 'required|integer',
        ]);

        $validator->after(function ($validator) use ($request) {

        });

        if ($validator->passes()) {
            $data = array(
                'area_id'      => $request->area_id,
                'road_name'    => $request->road_name,
                // 'full_address' => $request->full_address,
                'status'       => $request->status,
            );

            Road::where('id',$request->id)->update($data);
            return redirect()->route('roads')->with('success_msg', 'Road details updated successfully.');
        } else {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error_msg', 'Something went wrong please check your input.');
        }
    }
}
