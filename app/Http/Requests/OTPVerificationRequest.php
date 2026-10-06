<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class OTPVerificationRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'email_otp' => 'required|numeric|digits:4',
            // 'phone_otp' => 'required|numeric|digits:4',
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {

            // if(!isset($this->type_id)){
            //     $validator->errors()->add('type_id', 'The user type field is required.');
            // }
            $checkUser = User::where('id', $this->id)->where('status','0')->first();
            if(isset($this->email_otp)){ 
                if ($this->email_otp!=$checkUser->email_otp){
                    $validator->errors()->add('email_otp', 'Email OTP Does Not Match!.');
                }
            }

            // if(isset($this->phone_otp)){
            //     if ($this->phone_otp!=$checkUser->phone_otp){
            //         $validator->errors()->add('phone_otp', 'Phone OTP Does Not Match!.');
            //     }
            // }

        });

    }

}
