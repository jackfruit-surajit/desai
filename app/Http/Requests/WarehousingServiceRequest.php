<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class WarehousingServiceRequest extends FormRequest {

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

            'product_model_no'=> 'required',
            'product_part_no'=> 'required',
            'product_description'=> 'required',
            'product_type'=> 'required',
            'p_length'=> 'required',
            'p_height'=> 'required',
            'p_width'=> 'required',
            'p_weight'=> 'required',
            'quantity'=> 'required',
            'quantity_unit'=> 'required',
            'sku_numbers'=> 'required',
            'number_of_boxes'=> 'required',
            'storage_conditions'=> 'required',
            'storage_capacity'=> 'required',
            'packaging_requirements'=> 'required',
            'shelf_life'=> 'required',
            'security_needs'=> 'required',
            'warehouse_location'=> 'required',
            'warehouse_address'=> 'required',
            'inbound_outbound_schedules'=> 'required',
            'handling_instructions'=> 'required',
            'labeling_requirements'=> 'required',
            'stock_levels'=> 'required',
            'replenishment'=> 'required',
            'order_fulfilment'=> 'required',
            'order_processing'=> 'required',
            'shipping_preferences'=> 'required',
            'documentation'=> 'required',
            'compliance'=> 'required',
            'contract_terms'=> 'required',
            'cbm_rate'=> 'required',
            'per_sqft_occupied'=> 'required',
            'fixed_monthly_rate'=> 'required',
            'storage_duration_slabs'=> 'required',
            'handling_charges'=> 'required',
            'peak_season_surcharge'=> 'required',
            'inventory_turnover_based'=> 'required',
            'palletizing_fee'=> 'required',
            'min_billing_commitment'=> 'required',
            'per_skubin_fee'=> 'required',
            'hazardous_Control_storage'=> 'required',
            'vas'=> 'required',
            
            // 'transportation_mode'=> 'required',
            // 'frequency_of_shipments'=> 'required',
            // 'delivery_schedule'=> 'required',
            // 'transportation_requirements'=> 'required',
            // 'customs_clearance_requirements'=> 'required',
            // 'regulatory_compliance'=> 'required',
            // 'insurance_requirements'=> 'required',
            // 'value_added_services'=> 'required',
            // 'equipment_requirements'=> 'required',
            // 'other_special_requirements'=> 'required',
            // 'payment_terms'=> 'required',
            // 'terms_condition'=> 'required',
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {

        });

    }

}
