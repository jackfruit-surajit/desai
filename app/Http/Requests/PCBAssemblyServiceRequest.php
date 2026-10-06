<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class PCBAssemblyServiceRequest extends FormRequest {

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

            'product_photo' => 'required|array',
            'product_photo.*' => 'required|file',
            'gerber_file' => 'required',
            'bom_file' => 'required',
            'pcb_x_y_data' => 'required',

            // 'assembly_drawing' => 'required',
            // 'assembly_3d_drawing' => 'required',
            // 'schematics' => 'required',
            // 'approved_vendors_list' => 'required',
            // 'compo_type_qty' => 'required',

            'sample_pcba' => 'required',
            'pcb_type' => 'required',
            'pcb_layer' => 'required',
            'rohs_and_non_rohs_solder' => 'required',
            'assembly_compo_type' => 'required',
            'bare_pcb' =>'required',
            'material_scope' => 'required',
            'material_format' => 'required',
            'moq' => 'required',
            'moq_quantity' => 'required',
            'delivery_schedule' => 'required',
            'lead_times' => 'required',
            'quality_standards' => 'required',
            'testing_requirements' => 'required',
            'acceptance_criteria' => 'required',
            'packaging_type' => 'required',
            'shipping_method' => 'required',
            'shipping_destination' => 'required',
            'payment_terms' => 'required',
            'warranty_support' =>'required',
            'terms_condition' => 'required',
            'documentation_report' => 'required',
            'contract_terms' => 'required',
            'per_unit_cost'         => 'required|numeric',
            'bulk_quantity_cost'    => 'required|numeric',
            'fixed_monthly_cost'    => 'required|numeric',

            'smd_compo_qty'   => 'required|numeric',
            'th_compo_qty'    => 'required|numeric',
            'bga_pcb'         => 'required',

        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {

        });

    }

}
