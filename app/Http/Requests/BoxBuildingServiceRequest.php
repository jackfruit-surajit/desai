<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class BoxBuildingServiceRequest extends FormRequest {

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
            'product_name' => 'required',
            'product_code' => 'required',
            'product_description' => 'required',
            'material_scope'      => 'required',
            'material_format'     => 'required',
            'schematic_diagram'   => 'required|mimes:png,jpeg,jpg,JPEG,svg,webp,pdf,doc,docx,csv,xlsx,xls',
            'bom' => 'required|mimes:png,jpeg,jpg,JPEG,svg,webp,pdf,doc,docx,csv,xlsx,xls',
            'pcb_design_file' => 'required|mimes:png,jpeg,jpg,JPEG,svg,webp,pdf,doc,docx,csv,xlsx,xls',
            'component_specifications' => 'required|mimes:png,jpeg,jpg,JPEG,svg,webp,pdf,doc,docx,csv,xlsx,xls',
            'power_requirements' => 'required',
            'voltage' => 'nullable',
            'current' => 'nullable',
            'power' => 'nullable',
            'enclosure_design' => 'required',
            'mounting_requirements' => 'required',
            'mount_type' => 'nullable',
            'hole_pattern' => 'nullable',
            'operating_temperature' => 'required',
            'humidity_requirements' => 'required',
            'regulatory_compliance' => 'required',
            'safety_requirements' => 'required',
            'testing_requirements' => 'required',
            'inspection_requirements' => 'required',
            'test_equipment' => 'required',
            'packaging_requirements' => 'required',
            'shipping_requirements' => 'required',
            'labeling_and_marking' => 'required',
            'payment_terms' => 'required',
            'warranty_support' => 'required',
            'terms_condition' => 'required',
            'moq' => 'required',
            'moq_quantity' => 'required',
            'delivery_schedule' => 'required',
            'lead_times' => 'required',
            'other_lead_times' => 'nullable',
            'contract_terms' => 'required',
            'per_unit_cost' => 'required|numeric',
            'bulk_quantity_cost' => 'required|numeric',
            'fixed_monthly_cost' => 'required|numeric',
            
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {

            if(isset($this->power_requirements)){
                if($this->power_requirements == "Voltage" && empty($this->voltage)){
                    $validator->errors()->add('voltage', 'Voltage field is required.');
                }
                elseif($this->power_requirements == "Current" && empty($this->current)){
                        $validator->errors()->add('current', 'Current field is required.');
                }
                elseif($this->power_requirements == "Power" && empty($this->power)){
                        $validator->errors()->add('power', 'Power field is required.');
                }
            }

            if(isset($this->mounting_requirements)){
                if($this->mounting_requirements == "Mount type" && empty($this->mount_type)){
                        $validator->errors()->add('mount_type', 'Mount type field is required.');
                }
                elseif($this->mounting_requirements == "Hole pattern" && empty($this->hole_pattern)){
                        $validator->errors()->add('hole_pattern', 'Hole pattern field is required.');
                }
            }

            if(isset($this->lead_times)){
                if($this->lead_times == "others" && empty($this->other_lead_times)){
                        $validator->errors()->add('other_lead_times', 'Other lead times field is required.');           
                }
            }

        });

    }

}
