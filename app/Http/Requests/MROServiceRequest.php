<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class MROServiceRequest extends FormRequest {

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

            'product_type'=> 'required',
            'application_area'=> 'required',
            'product_features'=> 'required',
            'warranty'=> 'required',
            'service_centralized_facility'=> 'required',
            'onsite_service'=> 'required',
            'temporary_mobile_repair_camp'=> 'required',
            'unique_custom_service'=> 'required',
            'visual_inspection_basic_tests'=> 'required',
            'minor_part_replacement'=> 'required',
            'board_level_repairs'=> 'required',
            'advanced_diagnostics'=> 'required',
            'material_support'=> 'required',
            'service_site'=> 'required',
            'monthly_units'=> 'required',
            'material_supply_condition'=> 'required',
            'operationg_procedure_file'=> 'required',
            'physical_sample_reference'=> 'required',
            'board_type'=> 'required',
            'tests_perform'=> 'required',
            'transport_option'=> 'required',
            'goods_moved'=> 'required',
            'payment_terms'=> 'required',
            'contract_terms'=> 'required',
            'warranty_support'=> 'required',
            'terms_condition'=> 'required',
            'back_to_bench_amount'   => 'required|numeric',
            'onsite_amount'          => 'required|numeric',
            'mobile_camp_amount'     => 'required|numeric',
            'special_project_amount' => 'required|numeric',
            'refurbishment_with_material_amount'    => 'required|numeric',
            'refurbishment_without_material_amount' => 'required|numeric',
            'repair_l1_amount'    => 'required|numeric',
            'repair_l2_amount'    => 'required|numeric',
            'repair_l3_amount'    => 'required|numeric',
            'Diagnosis_l0_amount' => 'required|numeric',  

            // 'diagnosis'=> 'required',
            // 'bom'=> 'required',
            // 'flash_tools'=> 'required',
            // 'timing_preference'=> 'required',
            // 'packaging_temperature_control'=> 'required',
            // 'repair_timelines_response'=> 'required',
            // 'uptime_tat'=> 'required',
            // 'format_interval_reporting'=> 'required',
            // 'shared_updates'=> 'required',
            // 'anything_else'=> 'required',
            
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
        });

    }

}
