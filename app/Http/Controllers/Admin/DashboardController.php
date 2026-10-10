<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Validator;
use Hash;
use DB;
use Session;
use App\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        date_default_timezone_set("Asia/Calcutta");
        $user = Auth::guard('backend')->user();
        $hour = date('H');
        $dayTerm = ($hour > 17) ? "Evening" : (($hour > 12) ? "Afternoon" : "Morning");
        $data['message'] = "Good " . $dayTerm;
        $data['customer'] = DB::table('customers')->where('status', '!=', '2')->count();
        $data['sales_executive'] = DB::table('admins')->where('role_id', '2')->where('status', '!=', '2')->count();
        $data['product'] = DB::table('products')->where('status', '!=', '2')->count();
        $data['area'] = DB::table('area')->where('status', '!=', '2')->count();
        $data['route'] = DB::table('road')->where('status', '!=', '2')->count();
        $data['vehicle'] = DB::table('vehicle')->where('status', '!=', '2')->count();

        
        return view('admin.dashboard.dashboard',$data);


    }


    public function get_profile() {
        $id = Auth()->guard('backend')->user()->id;
        $model = DB::select('select * from admins where id = ?', [$id]);

        if ($model) {
            $model = array_shift($model);
            return view('admin.dashboard.profile', ['model' => $model]);
        }else{
            return redirect()->back()->withErrors($validator)->withInput()->with('error_msg', 'Something went wrong!');
        }
        
    }

    public function post_profile(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email',
                    'phone' => 'nullable|numeric|digits:10',
                    'image' => 'nullable|mimes:jpeg,webp,jpg,png,svg',
        ]);
        $validator->after(function($validator)use ($request) {

            $id = Auth()->guard('backend')->user()->id;
            $model = DB::select('select email,phone from admins where id = ?', [$id]);
            $model = array_shift($model);
            

            if($model->email !== $request->email){
                $checkUserEmail = DB::select('select email from admins where email = ? and status != ?', [$request->email,'3']);
                
                if (!empty($checkUserEmail)) {
                    $validator->errors()->add('email', 'This email address already taken.');
                }
            }

            if($model->phone !==  $request->phone){
                $checkUserPhone = DB::select('select phone from admins where phone = ? and status != ?', [$request->phone,'3']);
                if (!empty($checkUserPhone)) {
                    $validator->errors()->add('phone', 'This phone number already taken.');
                }
            }

        });
        if ($validator->passes()) {

            $id = Auth()->guard('backend')->user()->id;
            $model = DB::select('select image from admins where id = ?', [$id]);
            $model = array_shift($model);

            $input = [];
            $input = $request->all();   
            // print_r($input);exit;

            if ($request->hasFile('image')) {
                $sample_image = $request->file('image');
                $imagename = $this->rand_string(14) . '.' . $sample_image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/staff');
                $sample_image->move($destinationPath, $imagename);
                $input['image'] = $imagename;
            }else{
                $input['image'] = $model->image;
            }

            
            $update = DB::update('update admins set image = ?,name = ?, phone = ?, email = ? where id = ?',[$input['image'],$input['name'],$input['phone'],$input['email'],$id]);

            return redirect()->back()->with('success_msg', 'Profile updated successfully.');
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function post_change_password(Request $request) {
        $validator = Validator::make($request->all(), [
                    'current_password' => 'required',
                    'password' => 'required|min:6|max:16|regex:/^(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/',
                    'confirm_password' => 'required|same:password',
                        ], [
                    'password.regex' => 'Password must contain at-least 1 capital letter, 1 small letter and 1 number.'
        ]);
        $validator->after(function($validator)use ($request) {
            $id = Auth()->guard('backend')->user()->id;
            $model = DB::select('select password from admins where id = ?', [$id]);
            $model = array_shift($model);

            if (Hash::check($request->input('current_password'), $model->password) == false) {
                $validator->errors()->add('current_password', 'Your current password does not match.');
            }
        });
        if ($validator->passes()) {
            $id = Auth()->guard('backend')->user()->id;
            $password = Hash::make($request->input('password'));

            $update = DB::update('update admins set password = ? where id = ?',[$password,$id]);
            
            return redirect()->back()->with('success_msg', 'Password updated successfully.');
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }
    public function layout_change(Request $request){
        if($request->ajax()){
            $data_msg = [];

            $layout = $request->input('layout');

            Session::put('layout',$layout);
            
            return response()->json($data_msg);
        }
        
    }

    
    

}