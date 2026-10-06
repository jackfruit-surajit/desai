<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Application;
use Hash;
use App\Mail\SendOtpMail;
use App\Mail\ForgotpasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class IndexController extends Controller
{
    public function index(){
        return view('front.index');
    }

    public function login(){
        return view('front.login');
    }

    public function register(){
        return view('front.register');
    }

    public function service(){
        return view('front.service-page');
    }



    public function getMobileOtp(Request $request){
        $request->validate([
            'phone' => 'required|integer|digits:10',
            'email' => 'required|email',
        ]);

        if(User::where('email',$request->email)->exists()){
            return response()->json(['status' =>'error','msg' => 'Email ID already teken ! please enter another email address.']);
        } 

        Session::forget(['otp','valid_time']);
        $random_otp = rand(100000,999999);
        $mailData = [
            'title' => 'Mail from Swachh Bharat',
            'body' => 'One-time-password for registration',
            'otp' => $random_otp,
        ];

        if(Mail::to($request->email)->send(new SendOtpMail($mailData))){
            
                $mobileNumber = "91".$request->phone;
                $senderId = 'REINTC';
                $routeId = 4;
                $message = 'Your OTP is '.$random_otp.'. It is valid for 5 mins. Do not share with anyone.Re-Infotech';
                
                $serverUrl = 'sms.gngsms.com';
                $authKey = '2402ACnI3NU4eXB468959ed8P37';
                $templateId = '1107175568000231122';
                $this->sendsmsGET($mobileNumber,$senderId,$routeId,$message,$serverUrl,$authKey,$templateId);
                
            session()->put([
                'otp' => $random_otp,
                'valid_time' => time() + + (5*60),
            ]);

            return response()->json(['status' =>'success','msg' => 'OTP has been sent to your mobile number & email address.']);
        }else{
            return response()->json(['status' =>'error','msg'=>'Unauthorized error.']);
        }
    }
    
    
    public function getLoginOtp(Request $request){
        $request->validate([
            'phone' => 'required|integer|digits:10',
        ]);

        if(User::where('phone',$request->phone)->exists()){
            
            Session::forget(['otp','valid_time']);
            $random_otp = rand(100000,999999);
            
            $mobileNumber = "91".$request->phone;
            $senderId = 'REINTC';
            $routeId = 4;

            $message = 'Your OTP is '.$random_otp.'. It is valid for 5 mins. Do not share with anyone.Re-Infotech';
            $serverUrl = 'sms.gngsms.com';
            $authKey = '2402ACnI3NU4eXB468959ed8P37';
            $templateId = '1107175568000231122';
            $this->sendsmsGET($mobileNumber,$senderId,$routeId,$message,$serverUrl,$authKey,$templateId);
            
            // Session::put('otp', $random_otp);
            session()->put([
                'otp' => $random_otp,
                'valid_time' => time() + + (5*60),
            ]);
            return response()->json(['status' =>'success','msg' => 'OTP has been sent to your mobile number.']);
            
        }else{
            return response()->json(['status' =>'error','msg' => 'This mobile number is not registered.']);
        } 
    }
    
    
    function sendsmsGET($mobileNumber,$senderId,$routeId,$message,$serverUrl,$authKey,$templateId){

        $getData = 'mobiles='.$mobileNumber.'&message='.urlencode($message).'&sender='.$senderId.'&route='.$routeId.'&country=91'.'&DLT_TE_ID='.$templateId;

        //API URL
        $url="http://".$serverUrl."/api/sendhttp.php?authkey=".$authKey."&".$getData;
      
        $url = preg_replace("/ /", "%20", $url);
        $arrContextOptions=array(
          "ssl"=>array(
               "verify_peer"=>false,
               "verify_peer_name"=>false,
          ),
        );  
        $response = file_get_contents($url, false, stream_context_create($arrContextOptions));
        return $response;

    }
    

    public function register_old(Request $request){
         $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|integer|digits:10',
            'password' => 'required|string',
            'phone_otp' => 'required|integer',
        ]);

        $session_otp = Session::get('otp');
        $otp_validation_time = Session::get('valid_time');
        
        if($session_otp == $request->phone_otp && time() < $otp_validation_time){
            $resgister = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'password' => Hash::make($request->password),
                    ]);
            if($resgister){
                
                $credentials = $request->validate([
                    'email' => ['required', 'email'],
                    'password' => ['required'],
                ]);
                Auth::guard('frontend')->attempt($credentials);
                $request->session()->regenerate();
                
                return response()->json(['status' =>'success', 'msg'=> $request->name.' ! Thank you for Registration. Please login your account.']);
            }else{
                return response()->json(['status' =>'error', 'msg'=>'Unauthorized error.']);
            }
        }else{
            return response()->json(['status' =>'error', 'msg'=>'Invalid OTP.']);
        }
        
    }

    public function login_old(Request $request){
         $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('frontend')->attempt($credentials)) {
            $request->session()->regenerate();
            // return redirect()->route('application_form');
            return response()->json(['status' =>'success']);
        }else{
            return response()->json(['status' =>'error']);
            // return back()->withErrors(['email' => 'Invalid login credentials']);
        }
    }
    
    public function LoginWithOtp(Request $request){
        $request->validate([
            'phone' => 'required|integer',
            'otp'   => 'required|integer',
        ]);

        $user = User::where('phone',$request->phone)->first();
        $session_otp = Session::get('otp');
        $otp_validation_time = Session::get('valid_time');
        
        if($session_otp == $request->otp && $user && time() < $otp_validation_time){
            Auth::guard('frontend')->login($user);
            return response()->json(['status' =>'success']);
        }else{
            return response()->json(['status' =>'error', 'msg'=>'Invalid OTP.']);
        }
    }

    public function sendForgotPasswordlink(Request $request){
        $request->validate([
            'email' => 'required',
        ]);
        if(User::where('email',$request->email)->exists()){
            $token = base64_encode($request->email.'@@'.time());
            $link = route('forgotpassword',['token' => $token]);

            $mailData = [
            'title' => 'Mail from Swachh Bharat',
            'body' => 'Forgot Password link',
            'link' => $link,
        ];

        if(Mail::to($request->email)->send(new ForgotpasswordMail($mailData))){
            return response()->json(['status' =>'success','msg' => 'Password reset link sent to your email id.']);
        }else{
            return response()->json(['status' =>'error','msg'=>'Unauthorized error.']);
        }

        } 
    }

    public function forgotPassword(Request $request,$paramiter){
        $token = explode('@@',base64_decode($paramiter));
        $email = $token[0];
        if(User::where('email',$email)->exists()){
             if(time() < ($token[1] + (10*60))){
                return view('front.landing.change_password',compact('email'));
             }else{
                return redirect()->route('/');
             }  
        }else{
            return redirect()->route('/');
        }     
    }

    public function resetPassword(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required|string'
        ]);
        if(User::where('email',$request->email)->update(['password' => Hash::make($request->password)])){
            return response()->json(['status' =>'success','msg' => 'Password updated successfully.']);
        }else{
            return response()->json(['status' =>'error','msg' => 'Error!! while updating password.']);
        }
    }

    public function successPasswordUpdate(){
        return view('front.landing.success_password');
    }

    public function otpLogin(Request $request){
        //
    }

    public function logout(Request $request)
    {
        Auth::guard('frontend')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }


    public function contactUs(){
        return view('front.landing.contact_us');
    }

    public function dashboard(){
        Session::forget('otp');
        $user_id = Auth::guard('frontend')->user()->id;
        $check_application = Application::where('user_id',$user_id)->first(['status']);
        return view('front.landing.dashboard',compact('check_application'));
    }
    
    // public function downloadinvoice()
    // {
    //     $pdf = Pdf::loadView('pdf.invoice');
    //     // return $pdf->download('invoice.pdf');
    //     return $pdf->stream('invoice.pdf');
    // }

    
}
