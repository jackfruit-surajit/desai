<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Area;
use DB;
use URL;

class AreaManagementController extends Controller
{
    
    public function index(Request $request){
        return view('admin.area.index');
    }
    
    public function areaAdd(Request $request){
        return view('admin.area.add');
    }
    
    public function areaStore(Request $request){

        $request->validate([
            'area_name' => 'required|string|unique:area,area_name',
            'status' => 'required',
        ]);

        $insert = Area::create([
                'area_name' => $request->area_name,
                'status' => $request->status,
            ]);

        if($insert){
            return redirect()->route('areas')->withSuccess('Area added successfully.');
        }else{
           return redirect()->back()->withErrors('Error!! while adding area!!!'); 
        }

    }
    
    
    public function getAreaDatatable(Request $request) {
        
        $data = DB::table('area')->orderBy('id','desc')->get();
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
                                            data-href="' . Route("area-status-change", ['id' => base64_encode($model->id)]) . '"
                                            onchange="changeStatusStaff(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    
                return $status;
            })

            ->addColumn('action', function ($model) {

                $edit = $delete = '';

                // $edit = '<a href="' . Route("banner-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary" title="Edit banner"><i class="fa fa-edit"></i></span></a>';   
                $delete = '<a href="' . Route("area-delete", ['id' => base64_encode($model->id)]) . '" ><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash" title="Delete"></i> Delete</span></a>';
             
                return
                    '<div class="action-btns">'.
                        $edit . $delete.
                    '</div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    
    public function areaDelete($id) {
        $id = base64_decode($id);
        $model = DB::table('area')->where('id', $id)->first();
        
        if (!empty($model)) {

            if(DB::table('area')->where('id',$id)->delete()){
                return redirect()->route('areas')->withSuccess('Area deleted successfully.');
            }
            else{
                return redirect()->back()->withErrors('Error!! while deleting area!!!'); 
            }
        } else {
            return redirect()->back()->withErrors('Sorry ! No area details found.'); 
        }
    }
    
    public function areaStatusChange(Request $request,$id){
        $datareturn = [];
        $data = [];
        $input = [];
        $id = base64_decode($id);
        $input = $request->all();

        $data['status'] = $input['status'];
        $data['updated_at'] = date('Y-m-d H:i:s');

        $model = DB::table('area')->where('id',$id)->update($data);
        $datareturn['status'] = 200;
        $datareturn['msg'] = 'Area status updated successfully.';
        return response()->json($datareturn);
    }


 
    
}