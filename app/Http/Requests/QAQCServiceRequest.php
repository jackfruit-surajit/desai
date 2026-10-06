<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class QAQCServiceRequest extends FormRequest {

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

            'iso_certificate'=> 'required',
            'standard_quality_processes'=> 'required',
            'ensures_certificates'=> 'required',
            'SOPs'=> 'required',
            'external_audit'=> 'required',
            'operational_risks'=> 'required',
            'product_name'=> 'required',
            'acceptance_criteria'=> 'required',
            'standard_qc'=> 'required',
            'packaging_details'=> 'required',
            'previous_test_reports'=> 'required',
            'special_inspection'=> 'required',
            'incoming_materials'=> 'required',
            'specific_test_methods'=> 'required',
            'interface_validation'=> 'required',
            'compliance_standards'=> 'required',
            'packaging_verification'=> 'required',
            'sampling_method'=> 'required',
            'independent_inspection'=> 'required',
            'billing_address'=> 'required',
            'branch_office_address'=> 'nullable',
            'shipping_address'=> 'required',
            'different_shipping_address'=> 'nullable',
            'preferred_shipping_method'=> 'required',
            'contract_terms'=> 'required',
            'expected_target_price'=> 'required',
            'special_instructions'=> 'required',
            'legal_requirements'=> 'required',
            'additional_requirements'=> 'required',
            
            
            // 'current_certifications'=> 'required',
            // 'risk_registers'=> 'required',
            // 'current_sops'=> 'required',
            // 'control_plan'=> 'required',
            // 'product_specs'=> 'required',
            // 'acceptance_criteria'=> 'required',
            // 'supplier_details'=> 'required',
            // 'packaging_info'=> 'required',
            // 'qc_checklist'=> 'required',
            // 'shipping_documents'=> 'required',
            // 'factory_address'=> 'required',
            // 'sampling_plan'=> 'required',
            // 'sample_details'=> 'required',
            // 'spc_charts'=> 'required',
            // 'defective_samples'=> 'required',
            // 'packing_checklist'=> 'required',
            // 'supplier_data'=> 'required',
            // 'test_software_hardware'=> 'required',
            // 'target_markets'=> 'required',
            // 'packing_method'=> 'required',
            // 'visual_criteria'=> 'required',
            // 'billing_address'=> 'required',
            // 'branch_office_address'=> 'nullable',
            // 'shipping_address'=> 'required',
            // 'different_shipping_address'=> 'nullable',
            // 'preferred_shipping_method'=> 'required',
            // 'contract_terms'=> 'required',
            // 'expected_target_price'=> 'required',

            // 'special_instructions'=> 'required',
            // 'warranty_requirements'=> 'required',
            // 'legal_requirements'=> 'required',
            // 'additional_requirements'=> 'required',
 
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {

            if(isset($this->billing_address)){
                if($this->billing_address == "Branch Office" && empty($this->branch_office_address)){
                        $validator->errors()->add('branch_office_address', 'Branch office address field is required.');           
                }
            }
            if(isset($this->shipping_address)){
                if($this->shipping_address == "Different Address" && empty($this->different_shipping_address)){
                        $validator->errors()->add('different_shipping_address', 'Different shipping address field is required.');           
                }
            }
            
            // if(isset($this->shipping_address)){
            //     if($this->shipping_address == "Different Address" && empty($this->different_shipping_address)){
            //             $validator->errors()->add('different_shipping_address', 'Different shipping address field is required.');           
            //     }
            // }

        });

    }

}
