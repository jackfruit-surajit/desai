<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class AddressUpdateRequest extends FormRequest {

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
            'address_line_1' => 'required',
            // 'address_line_2' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pincode' => 'required',
            'landmark' => 'required',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            

            
        });
    }

}
