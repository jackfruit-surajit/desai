<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class ForgotOTPRequest extends FormRequest {

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
            'otp' => 'required|numeric|digits:4',
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {


            if(isset($this->forget_type)){ 
                $checkUser = User::where('id', $this->user_id)->first();
                if($this->forget_type=='by_email'){
                    if ($this->otp!=$checkUser->email_otp){
                        $validator->errors()->add('otp', 'OTP Does Not Match!.');
                    }
                }else{
                    if ($this->otp!=$checkUser->phone_otp){
                        $validator->errors()->add('otp', 'OTP Does Not Match!.');
                    }
                }
            }



        });

    }

}
