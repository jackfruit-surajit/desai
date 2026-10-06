<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;

class StatusWiseExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $item[0] = array(
           'application_id' => 'Application ID',
           'name'           => 'Applicant Name',
           'constituency'   => 'constituency_name',
           'official_name'  => 'Official Name',
           'application_type' => 'Application Type',
           'status'  => 'Status',
        );

        $results = Application::join('constituencies','applications.constituency_id','constituencies.id')
                        ->orderBy('applications.status','asc')->get(['applications.application_type','applications.application_id','applications.name','constituencies.constituency as constituency_name','applications.official_name','applications.status']);
       
                        
        foreach($results as $result){
            $res = array(
                'application_id' => $result->application_id,
                'name'  => $result->name,
                'constituency'  => $result->constituency_name,
                'official_name'  => $result->official_name,
                'application_type'  => checkApplicationType($result->application_type),
                'status' => checkStatus($result->status),
            );

            $item[] = $res;
        }
        unset($results,$result);
        return collect($item);
        
    }
}
