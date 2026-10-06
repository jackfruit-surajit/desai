<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class BasicServiceInquiryRequest extends FormRequest {

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
            'application_use' => 'required',
            'current_stage' => 'required',
            'service_start_date' => 'required|date|after:today',
            'service_duration' => 'required',
            'service_type' => 'required',
            'main_service' => 'required',
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {
            
            if(isset($this->main_service) && is_array($this->main_service)){
                
                if(in_array("Turnkey EMS Services", $this->main_service)){

                    if(empty($this->ems_core_services)){
                        $validator->errors()->add('ems_core_services', 'Turnkey EMS Core Services field is required.');
                    }else{

                        // if(in_array("Turnkey EMS Service", $this->ems_core_services)){
                        //     if(empty($this->turnkey_ems_solution)){
                        //         $validator->errors()->add('turnkey_ems_solution', 'Turnkey EMS Solution field is required.');
                        //     }else{

                        //         if($this->turnkey_ems_solution == "With Material"){
                        //             if($this->turnkey_ems_monthly_vol == ''){
                        //                 $validator->errors()->add('turnkey_ems_monthly_vol', 'Turnkey EMS Monthly Volume field is required.');
                        //             }else{
                        //                 if(!is_numeric($this->turnkey_ems_monthly_vol)){
                        //                     $validator->errors()->add('turnkey_ems_monthly_vol', 'Turnkey EMS Monthly Volume should contain number value.');
                        //                 }

                        //                 if($this->turnkey_ems_monthly_vol < 100 || $this->turnkey_ems_monthly_vol > 100000){
                        //                     $validator->errors()->add('turnkey_ems_monthly_vol', 'Turnkey EMS Monthly Volume Nos. between 100 to 100000');
                        //                 }

                        //             }

                        //             if($this->turnkey_ems_yearly_vol == ''){
                        //                 $validator->errors()->add('turnkey_ems_yearly_vol', 'Turnkey EMS Yearly Volume field is required.');
                        //             }else{
                        //                 if(!is_numeric($this->turnkey_ems_yearly_vol)){
                        //                     $validator->errors()->add('turnkey_ems_yearly_vol', 'Turnkey EMS Yearly Volume should contain number value.');
                        //                 }

                        //                 if($this->turnkey_ems_yearly_vol < 100 || $this->turnkey_ems_yearly_vol > 100000){
                        //                     $validator->errors()->add('turnkey_ems_yearly_vol', 'Turnkey EMS Yearly Volume Nos. between 100 to 100000');
                        //                 }

                        //             }
                        //         }
                        //     }
                        // }

                        if(in_array("PCB Assembly", $this->ems_core_services)){
                            if(empty($this->pcb_solution)){
                                $validator->errors()->add('pcb_solution', 'PCB Assembly Solution field is required.');
                            }else{
                                if($this->pcb_solution == "With Material"){

                                    if($this->pcb_monthly_vol == ''){
                                        $validator->errors()->add('pcb_monthly_vol', 'PCB Monthly Volume field is required.');
                                    }else{
                                        if(!is_numeric($this->pcb_monthly_vol)){
                                            $validator->errors()->add('pcb_monthly_vol', 'PCB Monthly Volume should contain number value.');
                                        }

                                        if($this->pcb_monthly_vol < 100 || $this->pcb_monthly_vol > 100000){
                                            $validator->errors()->add('pcb_monthly_vol', 'PCB Monthly Volume Nos. between 100 to 100000');
                                        }

                                    }

                                    if($this->pcb_yearly_vol == ''){
                                        $validator->errors()->add('pcb_yearly_vol', 'PCB Yearly Volume field is required.');
                                    }else{
                                        if(!is_numeric($this->pcb_yearly_vol)){
                                            $validator->errors()->add('pcb_yearly_vol', 'PCB Yearly Volume should contain number value.');
                                        }

                                        if($this->pcb_yearly_vol < 100 || $this->pcb_yearly_vol > 100000){
                                            $validator->errors()->add('pcb_yearly_vol', 'PCB Yearly Volume Nos. between 100 to 100000');
                                        }

                                    }

                                }
                            }

                            if(empty($this->pcb_services)){
                                $validator->errors()->add('pcb_services', 'PCB Services field is required.');
                            }
                        }

                        if(in_array("Battery Pack Assembly", $this->ems_core_services)){
                            if(empty($this->battery_pack_solution)){
                                $validator->errors()->add('battery_pack_solution', 'Battery Pack Solution field is required.');
                            }else{
                                if($this->battery_pack_solution == "Customize Manufacturing"){

                                    if($this->battery_cell_charging == ''){
                                        $validator->errors()->add('battery_cell_charging', 'Battery Cell Charging field is required.');
                                    }else{
                                        if(!is_numeric($this->battery_cell_charging)){
                                            $validator->errors()->add('battery_cell_charging', 'Battery Cell Charging should contain number value.');
                                        }

                                        if($this->battery_cell_charging < 100 || $this->battery_cell_charging > 100000){
                                            $validator->errors()->add('battery_cell_charging', 'Battery Cell Charging Nos. between 100 to 100000');
                                        }
                                    }

                                    if($this->battery_cell_qty == ''){
                                        $validator->errors()->add('battery_cell_qty', 'Battery Cell Qty field is required.');
                                    }else{
                                        if(!is_numeric($this->battery_cell_qty)){
                                            $validator->errors()->add('battery_cell_qty', 'Battery Cell Qty should contain number value.');
                                        }

                                        if($this->battery_cell_qty < 100 || $this->battery_cell_qty > 100000){
                                            $validator->errors()->add('battery_cell_qty', 'Battery Cell Qty Nos. between 100 to 100000');
                                        }
                                    }

                                    if($this->battery_cell_ir_v_testing == ''){
                                        $validator->errors()->add('battery_cell_ir_v_testing', 'Battery Cell IR/V Testing field is required.');
                                    }else{
                                        if(!is_numeric($this->battery_cell_ir_v_testing)){
                                            $validator->errors()->add('battery_cell_ir_v_testing', 'Battery Cell IR/V Testing should contain number value.');
                                        }

                                        if($this->battery_cell_ir_v_testing < 100 || $this->battery_cell_ir_v_testing > 100000){
                                            $validator->errors()->add('battery_cell_ir_v_testing', 'Battery Cell IR/V Testing Nos. between 100 to 100000');
                                        }
                                    }

                                    if($this->battery_cell_ir_v_qty == ''){
                                        $validator->errors()->add('battery_cell_ir_v_qty', 'Battery Cell IR/V Qty field is required.');
                                    }else{
                                        if(!is_numeric($this->battery_cell_ir_v_qty)){
                                            $validator->errors()->add('battery_cell_ir_v_qty', 'Battery Cell IR/V Qty should contain number value.');
                                        }

                                        if($this->battery_cell_ir_v_qty < 100 || $this->battery_cell_ir_v_qty > 100000){
                                            $validator->errors()->add('battery_cell_ir_v_qty', 'Battery Cell IR/V Qty Nos. between 100 to 100000');
                                        }
                                    }

                                    if(empty($this->battery_pack_capacity_testing)){
                                        $validator->errors()->add('battery_pack_capacity_testing', 'Battery Pack Capacity Testing field is required.');
                                    }
                                }
                            }
                        }

                    }

                } 
                
                if(in_array("Engineering & R&D Services", $this->main_service)){

                    if(empty($this->eng_core_services)){
                        $validator->errors()->add('eng_core_services', 'Engineering Core Services field is required.');
                    }else{

                        if(in_array("Rapid Prototyping & Pilot Production", $this->eng_core_services)){

                            if(empty($this->rapid_project_description)){
                                $validator->errors()->add('rapid_project_description', 'Rapid Project Description field is required.');
                            }

                            if($this->rapid_prototyping_testing == ''){
                                $validator->errors()->add('rapid_prototyping_testing', 'Rapid Prototyping Testing field is required.');
                            }else{
                                if(!is_numeric($this->rapid_prototyping_testing)){
                                    $validator->errors()->add('rapid_prototyping_testing', 'Rapid Prototyping Testing should contain number value.');
                                }

                                if($this->rapid_prototyping_testing < 100 || $this->rapid_prototyping_testing > 100000){
                                    $validator->errors()->add('rapid_prototyping_testing', 'Rapid Prototyping Testing Nos. between 100 to 100000');
                                }

                            }

                            if(empty($this->rapid_product_certification)){
                                $validator->errors()->add('rapid_product_certification', 'Rapid Product Certification field is required.');
                            }

                        }

                    }

                }
                
                if(in_array("SCM Services", $this->main_service)){

                    if(empty($this->scm_core_services)){
                        $validator->errors()->add('scm_core_services', 'SCM Core Services field is required.');
                    }else{

                        if(in_array("Sourcing Services", $this->scm_core_services)){

                            if(empty($this->sourcing_solution)){
                                $validator->errors()->add('sourcing_solution', 'Sourcing Solution field is required.');
                            }else{
                                if($this->sourcing_solution == "Specific Services"){
                                    if(empty($this->sourcing_specific_services)){
                                        $validator->errors()->add('sourcing_specific_services', 'Sourcing Specific Services field is required.');
                                    }
                                }
                            }

                        }

                        if(in_array("Warehousing Services", $this->scm_core_services)){

                            if(empty($this->warehousing_solution)){
                                $validator->errors()->add('warehousing_solution', 'Warehousing Solution field is required.');
                            }else{
                                if($this->warehousing_solution == "Specific Services"){

                                    if(empty($this->warehousing_general_storage)){
                                        $validator->errors()->add('warehousing_general_storage', 'Warehousing General Storage field is required.');
                                    }

                                    if(empty($this->warehousing_general_storage_area)){
                                        $validator->errors()->add('warehousing_general_storage_area', 'Warehousing General Storage Area field is required.');
                                    }else{
                                        if(!is_numeric($this->warehousing_general_storage_area)){
                                            $validator->errors()->add('warehousing_general_storage_area', 'Warehousing General Storage Area should contain number value.');
                                        }

                                        if($this->warehousing_general_storage_area <= 0){
                                            $validator->errors()->add('warehousing_general_storage_area', 'Warehousing General Storage Area should greater than 0');
                                        }
                                    }

                                    if(empty($this->warehousing_temp_controlled_storage)){
                                        $validator->errors()->add('warehousing_temp_controlled_storage', 'Warehousing Temperature Controlled Storage field is required.');
                                    }

                                    if(empty($this->warehousing_temp_controlled_storage_area)){
                                        $validator->errors()->add('warehousing_temp_controlled_storage_area', 'Warehousing Temperature Controlled Storage Area field is required.');
                                    }else{
                                        if(!is_numeric($this->warehousing_temp_controlled_storage_area)){
                                            $validator->errors()->add('warehousing_temp_controlled_storage_area', 'Warehousing Temperature Controlled Storage Area should contain number value.');
                                        }

                                        if($this->warehousing_temp_controlled_storage_area <= 0){
                                            $validator->errors()->add('warehousing_temp_controlled_storage_area', 'Warehousing Temperature Controlled Storage Area should greater than 0');
                                        }
                                    }

                                    if(empty($this->warehousing_bulk_storage)){
                                        $validator->errors()->add('warehousing_bulk_storage', 'Warehousing Bulk Storage field is required.');
                                    }

                                    if(empty($this->warehousing_bulk_storage_area)){
                                        $validator->errors()->add('warehousing_bulk_storage_area', 'Warehousing Bulk Storage Area field is required.');
                                    }else{
                                        if(!is_numeric($this->warehousing_bulk_storage_area)){
                                            $validator->errors()->add('warehousing_bulk_storage_area', 'Warehousing Bulk Storage Area should contain number value.');
                                        }

                                        if($this->warehousing_bulk_storage_area <= 0){
                                            $validator->errors()->add('warehousing_bulk_storage_area', 'Warehousing Bulk Storage Area should greater than 0');
                                        }
                                    }

                                    if(empty($this->warehousing_vertical_storage_racks)){
                                        $validator->errors()->add('warehousing_vertical_storage_racks', 'Warehousing Vertical Storage field is required.');
                                    }else{
                                        if(!is_numeric($this->warehousing_vertical_storage_racks)){
                                            $validator->errors()->add('warehousing_vertical_storage_racks', 'Warehousing Vertical Storage Area should contain number value.');
                                        }

                                        if($this->warehousing_vertical_storage_racks <= 0){
                                            $validator->errors()->add('warehousing_vertical_storage_racks', 'Warehousing Vertical Storage Area should greater than 0');
                                        }
                                    }

                                }
                            }
                            
                        }

                        if(in_array("Logistics IMPEX Services", $this->scm_core_services)){

                            if(empty($this->logistics_services)){
                                $validator->errors()->add('logistics_services', 'Logistics Services field is required.');
                            }

                        }

                    }

                }
                
                if(in_array("MRO Services", $this->main_service)){

                    if(empty($this->mro_core_services)){
                        $validator->errors()->add('mro_core_services', 'MRO Core Services field is required.');
                    }else{

                        if(in_array("Product Refurbishment Services", $this->mro_core_services)){

                            if(empty($this->repair_service)){
                                $validator->errors()->add('repair_service', 'Repair Service field is required.');
                            }

                            if($this->refurbish_monthly_vol == ''){
                                $validator->errors()->add('refurbish_monthly_vol', 'Refurbish Monthly Volume field is required.');
                            }else{
                                if(!is_numeric($this->refurbish_monthly_vol)){
                                    $validator->errors()->add('refurbish_monthly_vol', 'Refurbish Monthly Volume should contain number value.');
                                }

                                if($this->refurbish_monthly_vol < 100 || $this->refurbish_monthly_vol > 100000){
                                    $validator->errors()->add('refurbish_monthly_vol', 'Refurbish Monthly Volume Nos. between 100 to 100000');
                                }

                            }

                            if($this->refurbish_yearly_vol == ''){
                                $validator->errors()->add('refurbish_yearly_vol', 'Refurbish Yearly Volume field is required.');
                            }else{
                                if(!is_numeric($this->refurbish_yearly_vol)){
                                    $validator->errors()->add('refurbish_yearly_vol', 'Refurbish Yearly Volume should contain number value.');
                                }

                                if($this->refurbish_yearly_vol < 100 || $this->refurbish_yearly_vol > 100000){
                                    $validator->errors()->add('refurbish_yearly_vol', 'Refurbish Yearly Volume Nos. between 100 to 100000');
                                }

                            }

                        }
                    }

                }
                
                if(in_array("QA & QC Services", $this->main_service)){

                    if(empty($this->qa_qc_core_services)){
                        $validator->errors()->add('qa_qc_core_services', 'QA & QC Core Services field is required.');
                    }else{

                        if(in_array("QA Services", $this->qa_qc_core_services)){

                            if(empty($this->qa_services)){
                                $validator->errors()->add('qa_services', 'QA Services field is required.');
                            }

                        }

                        if(in_array("QC Services", $this->qa_qc_core_services)){

                            if(empty($this->qc_services)){
                                $validator->errors()->add('qc_services', 'QC Services field is required.');
                            }

                        }

                    }

                }
                
                if(in_array("Other Services", $this->main_service)){

                    if(empty($this->remarks)){
                        $validator->errors()->add('remarks', 'Remarks field is required.');
                    }

                }

            }

        });

    }

}
