<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class CheckoutRequest extends FormRequest {

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
            'delivery' => 'required', // Ensures delivery option is selected
            'address_line_1' => 'required_if:delivery,Home',
            'state' => 'required_if:delivery,Home',
            'city' => 'required_if:delivery,Home',
            'pincode' => 'required_if:delivery,Home',
            'landmark' => 'required_if:delivery,Home',
        ];
    }
    public function messages() {
        return [
            'address_line_1.required_if' => 'Address Line 1 is required for home delivery.',
            'state.required_if' => 'State is required for home delivery.',
            'city.required_if' => 'City is required for home delivery.',
            'pincode.required_if' => 'Pincode is required for home delivery.',
            'landmark.required_if' => 'Landmark is required for home delivery.',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            

            
        });
    }

}
