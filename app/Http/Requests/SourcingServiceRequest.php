<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class SourcingServiceRequest extends FormRequest {

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

            'project_title'=> 'required',
            'application_end_use'=> 'required',
            'current_stage'=> 'required',
            'material_part_number'=> 'required',
            'manufacturer_name'=> 'required',
            'material_description'=> 'required',
            'materials_package'=> 'required',
            'order_quantity'=> 'required',
            'annual_demand_quantity'=> 'required',
            'budget_price'=> 'required',
            'lead_time_requirements'=> 'required',
            'alternative_brand'=> 'required',
            'alternative_brand_details'=> 'nullable',
            'quality_standards'=> 'required',
            'material_specifications'=> 'required',
            'material_specifications_file' => 'required',
            'material_length'=> 'required',
            'material_height'=> 'required',
            'material_width'=> 'required',
            'material_weight'=> 'required',
            'tolerance_requirements'=> 'required',      
            'billing_address'=> 'required',
            'shipping_address'=> 'required',
            'custom_shipping_address'=> 'nullable',
            'preferred_shipping_method'=> 'required',
            'special_instructions'=> 'required',
            'warranty_requirements'=> 'required',
            'legal_requirements'=> 'required',
            'expected_target_price'=> 'required',
            
            'bom_materials' => 'required',
            'special_logistics_requirements'=> 'required',
            'taxes_duties_details'=> 'required',
            
            // 'customer_approved_vendor'=> 'required',
            // 'vendor_commercials'=> 'required',
            // 'additional_requirements'=> 'required',
            // 'technical_diagrams'=> 'required',

        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {
            if(isset($this->alternative_brand)){
                if($this->alternative_brand == "Yes"){
                    if(empty($this->alternative_brand_details)){
                        $validator->errors()->add('alternative_brand_details', 'Alternative brand details field is required.');
                    }

                }
            }

             if(isset($this->shipping_address)){
                if($this->shipping_address == "Custom" && empty($this->custom_shipping_address)){
                     $validator->errors()->add('custom_shipping_address', 'Custom Shipping Address field is required.');
                }
            }
            
            if(isset($this->bom_materials)){
                if($this->bom_materials == "yes" && empty($this->bom_file)){
                     $validator->errors()->add('bom_file', 'BOM file required.');
                }
            }
            
            
        });

    }

}
