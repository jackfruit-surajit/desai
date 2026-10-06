<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class LogisticsServiceRequest extends FormRequest {

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
            'license_number'=> 'required',
            'hs_code'=> 'required',
            'country_of_origin'=> 'required',
            'port_of_loading'=> 'required',
            'port_of_discharge'=> 'required',
            'incoterms'=> 'required',
            'customs_clearance_documentation'=> 'required',
            'import_duty_tax'=> 'required',
            'shipping_method'=> 'required',
            'courier_details'=> 'required',
            'shipping_handling'=> 'required',
            'packing_list'=> 'required',
            'transport_document'=> 'required',
            'lead_time'=> 'required',
            'billing_address'=> 'required',
            'shipping_address'=> 'required',
            'custom_shipping_address'=> 'nullable',
            'special_handling_instructions'=> 'required',
            'expected_target_price'=> 'required',

            // 'additional_shipping_requirements'=> 'required',
            // 'insurance_details'=> 'required',
            // 'compliance_certifications'=> 'required',
            // 'contract_terms'=> 'required',
            
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            if(isset($this->shipping_address)){
                if($this->shipping_address == "Custom" && empty($this->custom_shipping_address)){
                     $validator->errors()->add('custom_shipping_address', 'Custom Shipping Address field is required.');
                }
            }
        });
    }

}
