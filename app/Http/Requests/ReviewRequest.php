<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Models\Product;

class ReviewRequest extends FormRequest {

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
            'image' => 'array',
            'image.*' => 'nullable|mimes:png,jpeg,jpg,JPEG',
            'comment' => 'required',
            'rating' => 'required',
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
