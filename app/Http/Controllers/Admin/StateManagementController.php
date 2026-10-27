<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\State;
use DB;
use Validator;

class StateManagementController extends Controller
{
    /**
     * Display list of states
     */
    public function index(Request $request)
    {
        return view('admin.state.list');
    }

    /**
     * Fetch datatable records for states
     */
    public function getStateDatatable(Request $request)
    {
        $data = DB::table('states')->orderBy('id', 'desc')->get();

        return Datatables::of($data)
            ->addIndexColumn()
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
                                            data-href="' . route("state-status-change", ['id' => base64_encode($model->id)]) . '"
                                            onchange="changeStatusStaff(this)">
                                    </div>
                                </div>
                            </div>
                        </div>';
            })
            ->addColumn('action', function ($model) {
                $edit = '<a href="' . route("state-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary" title="Edit"><i class="fa fa-edit"></i> Edit</span></a> ';
                $delete = '<a href="' . route("state-delete", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-danger" title="Delete"><i class="fa fa-trash"></i> Delete</span></a>';

                return '<div class="action-btns">' . $edit . $delete . '</div>';
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at ? date('d M, Y', strtotime($model->created_at)) : '-';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show form to add a new state
     */
    public function create(Request $request)
    {
        return view('admin.state.add');
    }

    /**
     * Store a newly created state in database
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state_name' => 'required|string|max:255|unique:states,state_name',
            'state_code' => 'nullable|string|max:50',
            'status'     => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->all())
                ->with('error_msg', 'Something went wrong, please check your input.');
        }

        State::create([
            'state_name' => trim($request->state_name),
            'state_code' => $request->state_code ? strtoupper(trim($request->state_code)) : null,
            'status'     => $request->status,
        ]);

        return redirect()->route('states')->with('success', 'State added successfully.');
    }

    /**
     * Show form to edit an existing state
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        $model = State::where('id', $id)->first();

        if (!$model) {
            return redirect()->back()->with('error_msg', 'Invalid State!');
        }

        return view('admin.state.edit', compact('model', 'id'));
    }

    /**
     * Update an existing state
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'         => 'required|integer|exists:states,id',
            'state_name' => 'required|string|max:255|unique:states,state_name,' . $request->id,
            'state_code' => 'nullable|string|max:50',
            'status'     => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->all())
                ->with('error_msg', 'Something went wrong, please check your input.');
        }

        State::where('id', $request->id)->update([
            'state_name' => trim($request->state_name),
            'state_code' => $request->state_code ? strtoupper(trim($request->state_code)) : null,
            'status'     => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('states')->with('success', 'State details updated successfully.');
    }

    /**
     * Delete a state
     */
    public function delete($id)
    {
        $id = base64_decode($id);
        $model = State::where('id', $id)->first();

        if (!empty($model)) {
            $model->delete();
            return redirect()->route('states')->with('success', 'State deleted successfully.');
        } else {
            return redirect()->back()->withErrors('Sorry! No state details found.');
        }
    }

    /**
     * Toggle status via AJAX switch
     */
    public function statusChange(Request $request, $id)
    {
        $id = base64_decode($id);
        $input = $request->all();

        State::where('id', $id)->update([
            'status'     => $input['status'],
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 200,
            'msg'    => 'State status updated successfully.'
        ]);
    }
}

