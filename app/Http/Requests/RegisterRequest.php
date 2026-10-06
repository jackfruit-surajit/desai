<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class RegisterRequest extends FormRequest {

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
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|max:255',
            'phone' => 'required|numeric|digits:10',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {

            // if(!isset($this->type_id)){
            //     $validator->errors()->add('type_id', 'The user type field is required.');
            // }
            
            if(isset($this->email)){
                    $checkUser = User::where('email', $this->email)->where('status','<>','3')->count();
                    if ($checkUser > 0){
                        $validator->errors()->add('email', 'Email already in use.');
                }
            }

            if(isset($this->phone)){
                $checkUserPhone = User::where('phone', $this->phone)->where('status', '<>', '3')->count();
                if ($checkUserPhone > 0)
                    $validator->errors()->add('phone', 'Phone Number already in use.');
            }

        });

    }

}
