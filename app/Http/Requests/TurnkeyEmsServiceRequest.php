<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class TurnkeyEmsServiceRequest extends FormRequest {

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

            /*
            'product_description' => 'required',
            'product_dimensions' => 'required',
            'product_weight' => 'required',
            'environmental_requirements' => 'required',
            'approved_vendor_list' => 'required',
            'component_part_numbers' => 'required|numeric|min:1',
            'material_quantity' => 'required|numeric|min:1',
            'material_description' => 'required',

            'pcb_gerber_file' => 'required',
            'pcb_cad_file' => 'required',
            'pcb_other_design_file' => 'required',
            'mechanical_cad_file' => 'required',
            'mechanical_other_design_file' => 'required',
            'attachments' => 'required',

            'production_volume' => 'required|numeric|min:100|max:100000',
            'manufacturing_timeline' => 'required',
            'quality_requirements' => 'required',
            'certifications_compliance' => 'required',
            'test_requirements' => 'required',
            'test_fixtures_equipment' => 'required',
            'inspection_requirements' => 'required',
            'packaging_requirements' => 'required',
            'shipping_requirements' => 'required',
            'labeling_marking' => 'required',
            'warranty_requirements' => 'required',
            'support_requirements' => 'required',
            'regulatory_requirements' => 'required',
            'special_requirements' => 'required',
            'change_control_process' => 'required',
            'contract_terms' => 'required',
            'expected_target_price' => 'required|numeric',
            */

            

            // 'product_description'   => 'required',
            // 'product_specification' => 'required',
            // 'bom'                   => 'required',
            // 'pcb_design_file'       => 'required',
            // 'mechanical_design_file'=> 'required',
            // 'production_volume'     => 'required',
            // 'manufacturing_timeline'=> 'required',
            // 'quality_requirements'  => 'required',
            // 'material_scope'        => 'required',
            // 'material_format'       => 'required',
            // 'test_requirements'        => 'required',
            // 'test_fixtures_equipment'  => 'required',
            // 'inspection_requirements'  => 'required',
            // 'packaging_requirements'   => 'required',
            // 'shipping_requirements'    => 'required',
            // 'shipping_destination'     => 'required',
            // 'labeling_marking'         => 'required',
            // 'warranty_requirements'    => 'required',
            // 'support_requirements'     => 'required',
            // 'regulatory_requirements'  => 'required',
            // 'certifications_compliance' => 'required',
            // 'special_requirements'     => 'required',
            // 'change_control_process'=> 'required',

            'moq'                   => 'required',
            'moq_quantity'          => 'required',   
            'delivery_schedule'     => 'required',
            'lead_times'            => 'required',
            'testing_requirements'  => 'required',
            'acceptance_criteria'   => 'required',
            'documentation_report'  => 'required',
            'payment_terms'         => 'required',
            'warranty_support'      => 'required',
            'terms_condition'       => 'required',
            'contract_terms'        => 'required',
            'per_unit_cost'         => 'required|numeric',
            'bulk_quantity_cost'    => 'required|numeric',
            'fixed_monthly_cost'    => 'required|numeric',
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {

        });

    }

}
