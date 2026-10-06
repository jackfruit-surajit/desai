<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class BatteryPackAssemblyServiceRequest extends FormRequest {

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
            'battery_configuration' => 'required',
            'nominal_volate' => 'required',
            'typical_current' => 'required',
            'capacity_range' => 'required',
            'tolerance' => 'required',
            'application' => 'required',
            'other_application_name' => 'nullable',
            'environmental_conditions' => 'required',
            'performance_requirements' => 'required',
            'safety_features' => 'required',
            'regulatory_compliance' => 'required',
            'cell_type' => 'required',
            'testing_parameters' => 'required',
            'performance_metrics' => 'required',
            'cells_no' => 'required',
            'compliance_standards' => 'required',
            'data_reporting' => 'required',
            'data_reporting_file' => 'required|file',
            'cell_specifications' => 'required',
            'charging_protocols' => 'required',
            'safety_requirements' => 'required',
            'charging_equipment'=> 'required',
            'service_frequency'=> 'required',
            'compliance'=> 'required',
            'packaging_requirements'=> 'required',
            'shipping_requirements'=> 'required',
            'labeling_marking'=> 'required',
            'moq'=> 'required',
            'moq_quantity'=> 'required',
            'delivery_schedule'=> 'required',
            'lead_times'=> 'required',
            'other_lead_time'=> 'nullable',
            'payment_terms'=> 'required',
            'warranty_support'=> 'required',
            'terms_condition'=> 'required',
            'contract_terms'=> 'required',
            'per_unit_cost'=> 'required|numeric',
            'bulk_quantity_cost'=> 'required|numeric',
            'fixed_monthly_cost'=> 'required|numeric',       
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {

            if(isset($this->lead_times)){
                if($this->lead_times == "others" && empty($this->other_lead_time)){
                        $validator->errors()->add('other_lead_times', 'Other lead time field is required.');           
                }
            }

            if(isset($this->application)){
                if($this->application == "others" && empty($this->other_application_name)){
                        $validator->errors()->add('other_lead_times', 'Other application name field is required.');           
                }
            }

        });

    }

}
