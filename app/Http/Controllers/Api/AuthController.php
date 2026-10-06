<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendOtpMail;
use App\Mail\ForgotpasswordMail;
use Illuminate\Support\Facades\Mail;
use Hash;
use DB;
class AuthController extends Controller
{
    

    
    
    // public function sendMobileOtp(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         // 'phone'    => 'required|integer|digits:10|unique:users',
    //         'email'    => 'required|email|unique:users',
    //     ]);
        
    //     if ($validator->fails()) {
    //         return response()->json(['status' => 422,'message' => $validator->errors(), 'data'  =>[]], 422);
    //     }
        
    //     $random_otp = rand(100000,999999);
    //     $mailData = [
    //         'title' => 'OTP from MYLUCK',
    //         'body' => 'One-time-password for registration',
    //         'otp' => $random_otp,
    //     ];
        
    //     if(Mail::to($request->email)->send(new SendOtpMail($mailData))){
    //         return response()->json(['status' => 200,'message' => 'OTP send successfully.', 'otp' => $random_otp],200);
    //     }else{
    //         return response()->json(['status' => 200,'message' => "We're unable to send the OTP right now.", 'otp'  =>''],200);
    //     }
    // }
    
    
    // public function register(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'name'     => 'required|string',
    //         'email'    => 'required|email|unique:users',
    //         'phone'    => 'required|integer|digits:10|unique:users',
    //         'password' => 'required|string',
    //         'referral_code' => 'nullable|exists:users,self_referral_code',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(['status' => 422,'message' => $validator->errors(), 'data'  =>[]], 422);
    //     }

    //     $register = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'password' => Hash::make($request->password),
    //         'referral_by' => $request->referral_code,
    //         'subcription_status' => '1',
    //         'subcription_valid_to' => date('Y-m-d', strtotime('+30 days')),
    //     ]);

    //     if($register){
    //         $customer_code = substr($request->name, 0, 4).substr($request->phone, 0, 4).$register->id;
    //         $referral_code = 'MYLUCK'.$register->id.substr($request->phone, 0, 4);
    //         User::where('id',$register->id)->update(['customer_code' => $customer_code, 'self_referral_code' => $referral_code]);
            
    //         if($request->referral_code){
    //             $this->reward($request->referral_code,$register->id);
    //         }
            
    //         return response()->json(['status' => 200,'message' => 'Registration Successfully.', 'data'  =>[]],200);
    //     }else{
    //         return response()->json(['status' => 200,'message' => 'Internal Server Error.', 'data'  =>[]],200);
    //     }
    // }
    
    
    public function forgotPassword(Request $request){
         $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422,'message' => $validator->errors(), 'data'  =>[] ], 422);
        }
        
        if($user = User::where('email',$request->email)->first()){
            
            $token = $request->email.'@@'.$user->id;
            $mailData = [
                'title' => 'Reset Password Link',
                'body' => 'Reset Password Link is :',
                'link' => url('reset-password/'.base64_encode($token)),
            ];
        
        if(Mail::to($request->email)->send(new ForgotpasswordMail($mailData))){
            return response()->json(['status' => 200,'message' => "We've sent a password reset link to your registered email address.", 'data'  =>[]],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Internal Server Error.', 'otp'  =>''],200);
        }
            
        }else{
            return response()->json(['status' => 422,'message' => 'Please Enter your valid email'], 422);
        }
    }
    

    public function loginWithEmail(Request $request){
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors(), 'data' => []], 422);
        }

        $user = Admin::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 200, 'message' => 'Invalid credentials.', 'data' => []], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['status' => 200, 'message' => 'Login successful.', 'access_token' => $token, 'token_type' => 'Bearer','data' => $user]);  
    }
    
    public function login(Request $request){
        
        $validator = Validator::make($request->all(), [
            'mobile'   => 'required|string',
            'password' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status'  => 422, 'message' => $validator->errors(),'data'    => []], 422);
        }
    
        $user = Admin::where('phone', $request->mobile)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status'  => 401, 'message' => 'Invalid mobile number or password.', 'data'    => []], 401);
        }
    
        // Create Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([ 'status' => 200, 'message'      => 'Login successful.', 'access_token' => $token, 'token_type'   => 'Bearer', 'data' => $user], 200);
    }


    // public function logout(Request $request){
    //         $user = auth('api_backend')->user();
    //         if($user){
    //             $user->tokens()->delete();
    //             return response()->json(['status' => 200, 'message' => 'Logout successful.', 'data'  =>[]], 200);
    //         }
    //         else{
    //             return response()->json(['status' => 200, 'message' => 'Token expired.', 'data'  =>[]], 200);
    //         }
    // }
    
    public function logout(Request $request)
    {
        // dd(auth('api_backend')->user());
        if($request->user()->currentAccessToken()->delete()){
            return response()->json(['status' => 200, 'message' => 'Logout successful.', 'data'  =>[]], 200);
        }
        else{
            return response()->json(['status' => 200, 'message' => 'Token expired.', 'data'  =>[]], 200);
        }

    }
    
    
    // public function teacherProfile(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'user_id' => 'required|integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['status' => 422,'message' => $validator->errors(), 'data'  =>[]], 422);
    //     }
        
    //     $result = Admin::join('district','admins.district_id','district.id')
    //                     ->join('villages','admins.village_id','villages.id')
    //                     ->join('ward','admins.ward_id','ward.id')
    //                 ->where('admins.id',$request->user_id)->first(['admins.*','district.district_name','villages.village_name','ward.ward_name']);
    //     if($result){
    //         return response()->json(['status' => 200,'message' => 'M-pin Updated Successfully.', 'data'  =>$result],200);
    //     }else{
    //         return response()->json(['status' => 200,'message' => 'Internal Server Error.', 'data'  =>[]],200);
    //     }
    // }
    
    // public function setMPin(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'm_pin' => 'required|integer|max_digits:6',
    //         'user_id' => 'required|integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['status' => 422,'message' => $validator->errors(), 'data'  =>[]], 422);
    //     }
        
    //     $result = Admin::where('id',$request->user_id)->update(['m_pin' => $request->m_pin]);
    //     if($result){
    //         return response()->json(['status' => 200,'message' => 'M-pin Updated Successfully.', 'data'  =>[]],200);
    //     }else{
    //         return response()->json(['status' => 200,'message' => 'Internal Server Error.', 'data'  =>[]],200);
    //     }
    // }

    public function updateProfile(Request $request){
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|integer',
            'name'     => 'required|string',
            'email'    => "required|email|unique:users,email,$request->customer_id", // Email id not change
            'phone'    => 'required|integer|digits:10',
            'self_photo' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422,'message' => $validator->errors(), 'data'  =>[]], 422);
        }
        
        $data = array(
            'name' => $request->name,
            'email' => $request->email, 
            'phone' => $request->phone,
        );
            
        if ($request->hasFile('self_photo')) {
            $sample_file = $request->file('self_photo');
            $file_name = $this->rand_string(14) . '.' . $sample_file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/shopkeeper');
            $sample_file->move($destinationPath, $file_name);
            $data['image'] = $file_name;
        }

        $result = User::where('id',$request->customer_id)->update($data);
        if($result){
            return response()->json(['status' => 200,'message' => 'Profile Updated Successfully.', 'data'  =>[]],200);
        }else{
            return response()->json(['status' => 200,'message' => 'Internal Server Error.', 'data'  =>[]],200);
        }
    }



}
