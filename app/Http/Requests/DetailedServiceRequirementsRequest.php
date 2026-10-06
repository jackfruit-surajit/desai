<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class DetailedServiceRequirementsRequest extends FormRequest {

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
            'service_type' => 'required',
            'main_service' => 'required',
        ];
    }

    public function withValidator($validator) {

        $validator->after(function ($validator) {
            
            if(isset($this->main_service)){
                
                if(in_array("Turnkey EMS Services", $this->main_service)){

                    if(empty($this->turnkey_ems_core_services)){
                        $validator->errors()->add('turnkey_ems_core_services', 'Turnkey EMS Core Services field is required.');
                    }else{

                        if(in_array("PCBA Assembly", $this->turnkey_ems_core_services)){
                            if(empty($this->pcba_micro_services)){
                                $validator->errors()->add('pcba_micro_services', 'PCBA Micro Services field is required.');
                            }
                        }

                        if(in_array("Box Building", $this->turnkey_ems_core_services)){
                            if(empty($this->box_micro_services)){
                                $validator->errors()->add('box_micro_services', 'Box Building Micro Services field is required.');
                            }
                        }

                        if(in_array("Battery Pack Assembly", $this->turnkey_ems_core_services)){
                            if(empty($this->battery_micro_services)){
                                $validator->errors()->add('battery_micro_services', 'Battery Pack Assembly Micro Services field is required.');
                            }
                        }

                    }

                }
                
                if(in_array("Engineering & R&D Services", $this->main_service)){

                    if(empty($this->eng_core_services)){
                        $validator->errors()->add('eng_core_services', 'Engineering Core Services field is required.');
                    }else{

                        if(in_array("Integrated Product Design to Certification Solutions", $this->eng_core_services)){
                            if(empty($this->integrated_product_micro_services)){
                                $validator->errors()->add('integrated_product_micro_services', 'Integrated Product Micro Services field is required.');
                            }
                        }

                    }

                }
                
                if(in_array("SCM Services", $this->main_service)){

                    if(empty($this->scm_core_services)){
                        $validator->errors()->add('scm_core_services', 'SCM Core Services field is required.');
                    }else{

                        if(in_array("Sourcing Services", $this->scm_core_services)){
                            if(empty($this->sourcing_micro_services)){
                                $validator->errors()->add('sourcing_micro_services', 'Sourcing Micro Services field is required.');
                            }
                        }

                        if(in_array("Procurement", $this->scm_core_services)){
                            if(empty($this->procurement_micro_services)){
                                $validator->errors()->add('procurement_micro_services', 'Procurement Micro Services field is required.');
                            }
                        }

                        if(in_array("Import and Export", $this->scm_core_services)){
                            if(empty($this->import_export_micro_services)){
                                $validator->errors()->add('import_export_micro_services', 'Import and Export Micro Services field is required.');
                            }
                        }

                        if(in_array("Order Processing & Fulfilment", $this->scm_core_services)){
                            if(empty($this->order_processing_micro_services)){
                                $validator->errors()->add('order_processing_micro_services', 'Order Processing Micro Services field is required.');
                            }
                        }

                        if(in_array("Shipping & Logistics", $this->scm_core_services)){
                            if(empty($this->shipping_logistics_micro_services)){
                                $validator->errors()->add('shipping_logistics_micro_services', 'Shipping & Logistics Micro Services field is required.');
                            }
                        }

                        if(in_array("Packaging Solutions", $this->scm_core_services)){
                            if(empty($this->packaging_micro_services)){
                                $validator->errors()->add('packaging_micro_services', 'Packaging Micro Services field is required.');
                            }
                        }

                    }

                }
                
                if(in_array("MRO Services", $this->main_service)){

                    if(empty($this->mro_core_services)){
                        $validator->errors()->add('mro_core_services', 'MRO Core Services field is required.');
                    }else{

                        if(in_array("Repair Level Services", $this->mro_core_services)){
                            if(empty($this->repair_level_micro_services)){
                                $validator->errors()->add('repair_level_micro_services', 'Repair Level Micro Services field is required.');
                            }
                        }

                        if(in_array("Product Refurbishment Services", $this->mro_core_services)){
                            if(empty($this->product_refurbishment_micro_services)){
                                $validator->errors()->add('product_refurbishment_micro_services', 'Product Refurbishment Micro Services field is required.');
                            }
                        }
                    }

                }
                
                if(in_array("QA & QC Services", $this->main_service)){

                    if(empty($this->qa_qc_core_services)){
                        $validator->errors()->add('qa_qc_core_services', 'QA & QC Core Services field is required.');
                    }else{

                        if(in_array("ISO & OE Services", $this->qa_qc_core_services)){
                            if(empty($this->iso_oe_micro_services)){
                                $validator->errors()->add('iso_oe_micro_services', 'ISO & OE Micro Services field is required.');
                            }
                        }

                        if(in_array("Inspections and Testing Services", $this->qa_qc_core_services)){
                            if(empty($this->inspections_testing_micro_services)){
                                $validator->errors()->add('inspections_testing_micro_services', 'Inspections and Testing Micro Services field is required.');
                            }
                        }

                        if(in_array("Process Validation & Analysis Services", $this->qa_qc_core_services)){
                            if(empty($this->process_analysis_micro_services)){
                                $validator->errors()->add('process_analysis_micro_services', 'Process Validation & Analysis Micro Services field is required.');
                            }
                        }

                        if(in_array("IQC and Process Control and Monitoring and other Services", $this->qa_qc_core_services)){
                            if(empty($this->iqc_micro_services)){
                                $validator->errors()->add('iqc_micro_services', 'IQC Micro Services field is required.');
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
