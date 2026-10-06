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
use Route;

use Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

use App\Models\Role;

class RoleController extends Controller {
    
    public function get_list(Request $request) {
        $data = [];
        // $role = Role::find(1);

        // if ($role->hasPermission('settingssdsgsdgds')) {
        //     echo "This role can manage .";
        // } else {
        //     echo "This role cannot manage.";
        // }
        // exit;
        return view('admin.role.list', $data);
    }

    public function get_datatable(Request $request) {
        $data = DB::table('roles as t1')->where('name','!=','Admin')->orderby('t1.id','desc')
        ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('permissions', function($row) {
                return (strlen($row->permissions) > 100) ? substr($row->permissions, 0, 100) . '...' : $row->permissions;
            })
            ->editColumn('created_at', function ($model) {
                return !empty($model->created_at) ? date('d-m-Y', strtotime($model->created_at)) : '';
            })
            
            ->addColumn('action', function ($model) {

                $edit = ''; $delete = '';

                $edit = '<a href="' . Route("role-edit", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-primary"><i class="fa fa-edit"></i> Edit</span></a>';
                $delete = '<a href="javascript:;" onclick="deleteRole(this);" data-href="' . Route("role-delete", ['id' => base64_encode($model->id)]) . '"><span class="badge rounded-pill text-bg-danger"><i class="fa fa-trash"></i> Delete</span></a>';
                
                return
                    '<div class="action-btns">'.
                        $edit .
                    '</div>';
            })
            ->rawColumns(['image','created_at','status', 'action'])
            ->make(true);
    }

    public function get_add(){
        $data = [];
        $routes = Route::getRoutes();
        
        $actions = [];
		foreach ($routes as $route) {
            if (!empty($route->action['label'])) {
                $actions[explode('-', $route->getName())[0]][] = [
                    'label' => $route->action['label'],
                    'name' => $route->getName(),
                ];
			}            
		}
		$data['actions'] = $actions;
        // dd($data);
        return view('admin.role.add', $data);
    }

    public function post_add(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            // 'status' => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {


        });

        if ($validator->passes()) {

            $input = [];
            $data = [];
            $input = $request->all();
            unset($input['_token']);

            $data['name'] = $input['name'];
            // $data['status'] = $input['status'];
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            $role = Role::create($data);

            // Add all permissions to the role
            $permissions = $input['permissions'];
            
            $role->setPermissions($permissions);


            return redirect()->route('role-list')->with('success_msg', 'Role created successfully.');
        } else {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error_msg', 'Something went wrong please check your input.');
        }
    }


    public function get_edit($id) {
        $id = base64_decode($id);
        $model = Role::find($id);;
        if (!$model) {
            return redirect()->back()->with('error_msg', 'Invalid Link!');
        }
        $data['id'] = $id;
        $data['model'] = $model;
        $routes = Route::getRoutes();
        
        $actions = [];
		foreach ($routes as $route) {
            if (!empty($route->action['label'])) {
                $actions[explode('-', $route->getName())[0]][] = [
                    'label' => $route->action['label'],
                    'name' => $route->getName(),
                ];
			}            
		}
		$data['actions'] = $actions;
        return view('admin.role.edit', $data);
    }

    public function post_edit(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            //'status' => 'required',
            //'permissions' => 'nullable|array', // Permissions should be an array
            //'permissions.*' => 'string', // Each permission should be a string
        ]);

        $validator->after(function ($validator) use ($request) {


        });

        if ($validator->passes()) {

            $input = [];
            $data = [];
            $input = $request->all();


            // $data['status'] = $input['status'];

            $role = Role::findOrFail($id);

            $role->name = $input['name'];
            $role->updated_at = date('Y-m-d H:i:s');
            $role->save();
            // Update the permissions as JSON
            $permissions = $input['permissions']; // Default to an empty array if no permissions are selected
            // $role->permissions = array_fill_keys($permissions, true); // Store as key-value pairs (key => true)
    
            // Save the role
            
            $role->setPermissions($permissions);

            return redirect()->route('role-list')->with('success_msg', 'Role updated successfully.');
        } else {
            return redirect()->back()->withErrors($validator)->withInput()->with('error_msg', 'Something went wrong please check your input.');
        }
    }

    public function delete($id) {
        $data = [];
        $input = [];
        $id = base64_decode($id);

        $model = DB::table('roles')->where('id', $id)->first();
        
        if (!empty($model)) {

            $input['status'] = '2';
            $input['updated_at'] = date('Y-m-d H:i:s');

            // DB::table('roles')->where('id',$id)->update($input);
            DB::table('roles')->where('id',$id)->delete();

            $data['status'] = 200;
            $data['msg'] = 'Roles deleted successfully.';
        } else {
            $data['msg'] = 'Sorry ! No User details found.';
        }
        return response()->json($data);
        //--- Redirect Section Ends     
    }

    
    

}