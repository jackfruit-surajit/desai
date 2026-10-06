<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Auth;
use DB;

class ApplicationExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $division = Auth::guard('backend')->user()->division;
        $item[0] = array(
           'application_id' => 'Application ID',
           'name'           => 'Applicant Name',
           'house_owner_name'  => 'House Owner Name',
           'email'  => 'Email',
           'mobile'  => 'Mobile No',
           'gender'  => 'Gender',
           'district'  => 'District',
           'taluka'  => 'Taluka',
           'constituency'   => 'Constituency',
           'panchayat'      => 'Panchayat',
           'application_type' => 'Application Type',
           'official_name'  => 'Official Name',
           'community_complex_location' => 'Community Complex Location',
           'landmark'  => 'Landmark',
           'secretary'  => 'Secretary',
           'status'  => 'Status',
        );

        $data = Application::leftJoin('talukas','applications.taluka_id','talukas.id')->leftJoin('constituencies','applications.constituency_id','constituencies.id');
                if($division){
                    $data->where('applications.division',$division);
                }
                if ($this->request->taluka) {
                    $data->where('applications.taluka_id', $this->request->taluka);
                }
                if ($this->request->constituency) {
                    $data->where('applications.constituency_id', $this->request->constituency);
                }
                if ($this->request->application_type) {
                    $data->where('applications.application_type', $this->request->application_type);
                }
                if ($this->request->fstatus != 'all') {
                    $data->where('applications.status', $this->request->fstatus);
                }elseif($this->request->status){
                    $data->where('applications.status', $this->request->status);
                }
            $data = $data->orderBy('applications.id','desc')->get(['applications.*','talukas.taluka','constituencies.constituency']);


         foreach($data as $result){
            $res = array(
                'application_id' => $result->application_id,
                'name'  => $result->name,
                'house_owner_name'  => $result->house_owner_name,
                'email'  => $result->email,
                'mobile'  => $result->mobile,
                'gender'  => $result->gender,
                'district'  => $result->district,
                'taluka'  => $result->taluka,
                'constituency'  => $result->constituency,
                'panchayat'  => $result->panchayat,
                'application_type'  => checkApplicationType($result->application_type),
                'official_name'  => $result->official_name,
                'community_complex_location'  => $result->community_complex_location,
                'landmark'  => $result->landmark,
                'secretary'  => $result->secretary,
                'status' => checkStatus($result->status),
            );

            $item[] = $res;
        }

        unset($data,$result);
        return collect($item);
    }
}
