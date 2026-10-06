<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use DB;
use App\Models\User;
use App\Models\Admin;

class AuthController extends Controller {

    public function get_login() {
        $data = [];
        return view('admin.auth.login', $data);
    }

    public function post_login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->passes()) {

            // $model = Admin::where('email', $request->input('email'))->where('status', '1')->first();
            $model = Admin::where('email', $request->input('email'))->where('status', '1')->whereIn('role_id',[1,2])->first();


            if ($model) {

                if (Hash::check($request->password, $model->password)) {

                    $model->last_login = date('Y-m-d H:i:s');

                    $model->save();

                    Auth::guard('backend')->login($model);

                    $user_id = Auth()->guard('backend')->user()->id;
                    $ip = $this->get_user_ip();

                    $prev_login = DB::select('select * from login_history where user_id = ? and ip = ?', [$user_id, $ip]);

                    if ($prev_login) {
                        $prev_login = array_shift($prev_login);
                    }
                    
                    // echo "<pre>";print_r($prev_login);exit;

                    if ($prev_login) {

                        if ($prev_login->type == 'login') {

                            $minutes_to_add = 60;
                            $time = new \DateTime(date('Y-m-d H:i:s'));
                            $time->add(new \DateInterval('PT' . $minutes_to_add . 'M'));
                            $stamp = $time->format('Y-m-d H:i:s');

                            if (date('Y-m-d H:i:s') > $stamp) {
                                $upd_time = $stamp;
                            } else {
                                $upd_time = date('Y-m-d H:i:s');
                            }

                            $login = DB::insert('insert into login_history (type,user_id,ip,created_at) values (?,?,?,?)', ['logout',$user_id,$ip,$upd_time]);

                            
                        }
                    }

                    $created_at = date('Y-m-d H:i:s');

                    $login = DB::insert('insert into login_history (type,user_id,ip,created_at) values (?,?,?,?)', ['login',$user_id,$ip,$created_at]);

                    // echo "<pre>";print_r($login);exit;

                    return redirect()->route('admin-dashboard')->with('success_msg', 'You have successfully login');

                }else {
                    return redirect()->back()->withErrors($validator)->withInput()->with('error_msg', 'Login Failed!! Please check your credentials');
                }

            } else {
                return redirect()->back()->withErrors($validator)->withInput()->with('error_msg', 'Login Failed!! Please check your credentials');
            }

        } else {
            return redirect()->back()->withErrors($validator)->withInput()->with('error_msg', 'Login Failed!! Please check the below error');
        }

    }

    public function logout() {

        $ip = $this->get_user_ip();
        $user_id = Auth()->guard('backend')->user()->id;
        $type = 'logout';
        $created_at = date('Y-m-d H:i:s');

        $login = DB::insert('insert into login_history (type,user_id,ip,created_at) values (?,?,?,?)', [$type,$user_id,$ip,$created_at]);

        Auth::guard('backend')->logout();
        return redirect('admin/login')->with('success_msg', 'You have been successfully logout !!');
    }




}