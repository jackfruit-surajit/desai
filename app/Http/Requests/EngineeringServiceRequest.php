<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class EngineeringServiceRequest extends FormRequest {

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

            // 'attachments' => 'required',           

            // 'product_name' => 'required',
            // 'product_description' => 'required',
            // 'type_of_service' => 'required',
            // 'other_service' => 'nullable',
            // 'product_category' => 'required',
            // 'other_product_category' => 'nullable',
            
            // 'features_functionalities' => 'required',
            // 'processor' => 'required',
            // 'specific_recommondation' => 'nullable',

            // 'wireless_communication' => 'required',
            // 'wireless' => 'nullable',
            // 'other_wireless' => 'nullable',

            // 'power_requirements' => 'required',
            // 'operating_environment' => 'required',
            // 'scope_of_work' => 'required',

            // 'estimated_budget' => 'nullable',

            // 'expected_timeline' => 'required',
            // 'prototype_requirement' => 'required',
            // 'regulatory_compliance_requirements' => 'required',
            // 'confidentiality_agreement' => 'required',
            // 'additional_requirements' => 'nullable',

            // 'contract_terms' => 'required',
            // 'expected_target_price' => 'required|numeric',

            

            'product_name'              => 'required',
            'product_description'       => 'required',
            'type_of_service'           => 'nullable',
            //'other_service'             => 'nullable',
            'product_category'          => 'required',
            'other_product_category'    => 'nullable',
            'features_functionalities'  => 'required',
            'processor'                 => 'required',
            'specific_recommondation'   => 'nullable',
            'wireless_communication'    => 'required',
            'wireless'                  => 'nullable',
            'other_wireless'            => 'nullable',
            'power_requirements'        => 'required',
            'operating_environment'     => 'required',
            'scope_of_work'             => 'required',
            'estimated_budget'          => 'nullable',
            'expected_timeline'         => 'required',
            'prototype_requirement'     => 'required',
            'regulatory_compliance_requirements' => 'required',
            'confidentiality_agreement' => 'required',
            'additional_requirements'   => 'nullable',
            'contract_terms'            => 'required',
            'expected_target_price'     => 'required',


        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {

            if(isset($this->type_of_service)){
                if($this->type_of_service == "Other"){
                    if(empty($this->other_service)){
                        $validator->errors()->add('other_service', 'Other Service field is required.');
                    }
                }
            }

            if(isset($this->product_category)){
                if($this->product_category == "Other"){
                    if(empty($this->other_product_category)){
                        $validator->errors()->add('other_product_category', 'Other Product Category field is required.');
                    }
                }
            }

            if(isset($this->processor)){
                if($this->processor == "Yes"){
                    if(empty($this->specific_recommondation)){
                        $validator->errors()->add('specific_recommondation', 'specific recommondation field is required.');
                    }
                }
            }

            if(isset($this->wireless_communication)){
                if($this->wireless_communication == "Yes"){
                    if(empty($this->wireless)){
                        $validator->errors()->add('wireless', 'wireless field is required.');
                    }
                }
            }

            if(isset($this->wireless)){
                if($this->wireless == "Other"){
                    if(empty($this->other_wireless)){
                        $validator->errors()->add('other_wireless', 'Other Wireless field is required.');
                    }
                }
            }

        });

    }

}
