<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class InquiryRequest extends FormRequest {

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
            'phone' => 'required|numeric',

            'organization_name' => 'required',
            'industry_type'     => 'required',
            // 'website_url'       => 'nullable|url',
            'address'           => 'required|string',
            'state'             => 'required|string',
            'city'              => 'required|string',
            'pin_code'          => 'required|integer',
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {
            
            // if(isset($this->email)){
            //         $checkUser = User::where('email', $this->email)->where('status','<>','3')->count();
            //         if ($checkUser > 0){
            //             $validator->errors()->add('email', 'You are already submitted a inquiry!');
            //     }
            // }

            // if(isset($this->phone)){
            //     $checkUserPhone = User::where('phone', $this->phone)->where('status', '<>', '3')->count();
            //     if ($checkUserPhone > 0)
            //         $validator->errors()->add('phone', 'You are already submitted a inquiry!');
            // }

        });

    }

}
