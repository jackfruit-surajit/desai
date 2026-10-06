<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class ThroughHoleAssemblyServiceRequest extends FormRequest {

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

            'component_type'    => 'required',
            'component_size'    => 'required',
            'component_pitch'   => 'required',
            'component_quantity'=> 'required',
            'pcb_type'     => 'required',
            'pcb_thickness'=> 'required',
            'pcb_size'     => 'required',
            'pcb_material' => 'required',
            'solder_type'=> 'required',
            'solder_temperature'=> 'required',
            'solder_wave_height'=> 'required',
            'moq'=> 'required',
            'moq_quantity'=> 'required',
            'delivery_schedule'=> 'required',
            'lead_time'=> 'required',
            'other_lead_time'=> 'nullable',
            'testing_requirements' => 'required',
            'acceptance_criteria'  => 'required',
            'documentation_report' => 'required',
            'defect_classification'=> 'required',
            'packaging_type'       => 'required',
            'shipping_method'      => 'required',
            'shipping_destination' => 'required',
            'payment_terms'     => 'required',
            'warranty_support'  => 'required',
            'terms_condition'   => 'required',
            'contract_terms'    => 'required',
            'per_unit_cost'     => 'required|numeric',
            'bulk_quantity_cost'=> 'required|numeric',
            'fixed_monthly_cost'=> 'required|numeric',

            // 'insertion_method'=> 'required',
            // 'insertion_depth'=> 'required',
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {
            if(isset($this->lead_time)){
                if($this->lead_time == "others" && empty($this->other_lead_time)){
                        $validator->errors()->add('other_lead_time', 'Other lead time field is required.');           
                }
            }
        });

    }

}
