<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use DB;

class TrackOrderRequest extends FormRequest {

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
            'order_id' => 'required',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $model = DB::table('orders')->where('order_number',$this->order_id)->first();
            if (empty($model)) {
                
                $validator->errors()->add('order_id', "Order not found!");
            }
        });
    }

}
