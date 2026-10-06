<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Models\Product;

class HotelBookingRequest extends FormRequest {

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
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date',
            'rooms' => 'required|numeric|min:1',
            'adults' => 'required|numeric|min:1',
            'childs' => 'nullable|numeric|min:0',
            'payment_gateway' => 'required'
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {

            // if(!isset($this->type_id)){
            //     $validator->errors()->add('type_id', 'The user type field is required.');
            // }


        });

    }

}
