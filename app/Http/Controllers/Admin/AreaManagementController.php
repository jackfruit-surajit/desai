<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Area;
use App\Models\State;
use DB;
use URL;

class AreaManagementController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.area.index');
    }

    public function areaAdd(Request $request)
    {
        $states = State::where('status', '1')->orderBy('state_name', 'asc')->get();
        return view('admin.area.add', compact('states'));
    }

    public function areaStore(Request $request)
    {
        $request->validate([
            'state_id'  => 'required|integer|exists:states,id',
            'area_name' => 'required|string|unique:area,area_name',
            'status'    => 'required|in:0,1',
        ]);

        $insert = Area::create([
            'state_id'  => $request->state_id,
            'area_name' => trim($request->area_name),
            'status'    => $request->status,
        ]);

        if ($insert) {
            return redirect()->route('areas')->withSuccess('Area added successfully.');
        } else {
            return redirect()->back()->withErrors('Error! while adding area!');
        }
    }

    public function getAreaDatatable(Request $request)
    {
        $data = DB::table('area')
            ->leftJoin('states', 'area.state_id', '=', 'states.id')
            ->select('area.*', 'states.state_name')
            ->orderBy('area.id', 'desc')
            ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('state_name', function ($model) {
                return $model->state_name ? '<strong>' . e($model->state_name) . '</strong>' : '<span class="badge bg-secondary">Not Assigned</span>';
            })
            ->editColumn('status', function ($model) {
                $checked = ($model->status == '1') ? 'checked' : '';
                return '<div class="container">
                            <div class="row text-center">
                                <div class="col-md-12 offset-md-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                        type="checkbox" 
                                        role="switch" 
                                        id="flexSwitchCheckChecked_' . $model->id . '" ' . $checked . ' 
                                        data-href="' . Route("area-status-change", ['id' => base64_encode($model->id)]) . '"
                                        onchange="changeStatusStaff(this)">
                                    </div>
                                </div>
                            </div>
                        </div>';
            })
            ->addColumn('action', function ($model) {
                $edit = '<a href="' . Route("area-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary" title="Edit"><i class="fa fa-edit"></i> Edit</span></a> ';
                $delete = '<a href="' . Route("area-delete", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-danger" title="Delete"><i class="fa fa-trash"></i> Delete</span></a>';

                return '<div class="action-btns">' . $edit . $delete . '</div>';
            })
            ->rawColumns(['state_name', 'status', 'action'])
            ->make(true);
    }

    public function areaEdit($id)
    {
        $id = base64_decode($id);
        $model = Area::where('id', $id)->first();

        if (!$model) {
            return redirect()->back()->withErrors('Sorry! No area details found.');
        }

        $states = State::where('status', '1')->orderBy('state_name', 'asc')->get();
        return view('admin.area.edit', compact('model', 'states', 'id'));
    }

    public function areaUpdate(Request $request)
    {
        $request->validate([
            'id'        => 'required|integer|exists:area,id',
            'state_id'  => 'required|integer|exists:states,id',
            'area_name' => 'required|string|unique:area,area_name,' . $request->id,
            'status'    => 'required|in:0,1',
        ]);

        Area::where('id', $request->id)->update([
            'state_id'  => $request->state_id,
            'area_name' => trim($request->area_name),
            'status'    => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('areas')->withSuccess('Area updated successfully.');
    }

    public function areaDelete($id)
    {
        $id = base64_decode($id);
        $model = DB::table('area')->where('id', $id)->first();

        if (!empty($model)) {
            if (DB::table('area')->where('id', $id)->delete()) {
                return redirect()->route('areas')->withSuccess('Area deleted successfully.');
            } else {
                return redirect()->back()->withErrors('Error! while deleting area!');
            }
        } else {
            return redirect()->back()->withErrors('Sorry! No area details found.');
        }
    }

    public function areaStatusChange(Request $request, $id)
    {
        $id = base64_decode($id);
        $input = $request->all();

        DB::table('area')->where('id', $id)->update([
            'status'     => $input['status'],
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 200,
            'msg'    => 'Area status updated successfully.'
        ]);
    }
}
