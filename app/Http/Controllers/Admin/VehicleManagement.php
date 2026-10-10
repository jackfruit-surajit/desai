<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Vehicle;
use App\Models\Area;
use DB;
use URL;
use Auth;
use Validator;
use Hash;

class VehicleManagement extends Controller
{
    
    public function index(Request $request){
        return view('admin.vehicle.list');
    }

    public function getVehicleDatatable(Request $request) {
        $data = DB::table('vehicle')->orderBy('vehicle.id','desc')->get(['vehicle.*']);

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
                                            data-href="' . Route("vehicle-status-change", ['id' => base64_encode($model->id)]) . '"
                                            onchange="changeStatusStaff(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    
                return $status;
            })

            ->addColumn('action', function ($model) {

                $edit = $delete ='';

                $edit = '<a href="' . Route("vehicle-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary" title="Edit"><i class="fa fa-edit"></i> Edit</span></a>';    
                $delete = '<a href="' . Route("vehicle-delete", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash" title="Delete"></i> Delete</span></a>';

                return
                    '<div class="action-btns">'.
                        $edit . $delete.
                    '</div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create(Request $request){
        return view('admin.vehicle.add');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'vehicle_name'=> 'required|string',
            'vehicle_no'  => 'required|string',
            'fuel_type'   => 'nullable|string',
            'status'      => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {

        });

        if ($validator->passes()) {
            $data = array(
                'vehicle_name'=> $request->vehicle_name,
                'vehicle_no'  => $request->vehicle_no,
                'fuel_type'   => $request->fuel_type,
                'status'      => $request->status,
            );

            Vehicle::create($data);
            return redirect()->route('vehicles')->with('success_msg', 'Vehicle details added successfully.');
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

        $model = DB::table('vehicle')->where('id',$id)->update($data);
        $datareturn['status'] = 200;
        $datareturn['msg'] = 'Vehicle status updated successfully.';
        return response()->json($datareturn);
    }

    public function delete($id) {
        $id = base64_decode($id);
        $model = DB::table('vehicle')->where('id', $id)->first();
        
        if (!empty($model)) {

            if(DB::table('vehicle')->where('id',$id)->delete()){
                return redirect()->route('vehicles')->withSuccess('Vehicle deleted successfully.');
            }
            else{
                return redirect()->back()->withErrors('Error!! while deleting vehicle!!!'); 
            }
        } else {
            return redirect()->back()->withErrors('Sorry ! No vehicle details found.'); 
        }
    }
      
    public function edit($id) {
        $id = base64_decode($id);
        $model = Vehicle::where('id', $id)->first();
        if (!$model) {
            return redirect()->back()->with('error_msg', 'Invalid Link!');
        }
        $data['id'] = $id;
        $data['model'] = $model;
        return view('admin.vehicle.edit', $data);
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'vehicle_name'=> 'required|string',
            'vehicle_no'  => 'required|string',
            'fuel_type'   => 'nullable|string',
            'status'      => 'required',
            'id' => 'required|integer',
        ]);

        $validator->after(function ($validator) use ($request) {

        });

        if ($validator->passes()) {
            $data = array(
                'vehicle_name'  => $request->vehicle_name,
                'vehicle_no'    => $request->vehicle_no,
                'fuel_type'     => $request->fuel_type,
                'status'        => $request->status,
            );

            Vehicle::where('id',$request->id)->update($data);
            return redirect()->route('vehicles')->with('success_msg', 'Vehicle details updated successfully.');
        } else {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error_msg', 'Something went wrong please check your input.');
        }
    }
}
