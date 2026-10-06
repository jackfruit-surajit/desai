<?php

namespace App\Exports;

use App\Models\Application;
use App\Models\Constituency;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;

class ConstituencyWiseExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        //==========================================================> New <======================================================//

        $item[] = array(
          'application_type'  => 'New Connections',
        );

        $item[] = array(
          'constituency'      => 'Constituency',
          'total_application' => 'Total Applications Recieved',
          'unscrutinised'     => 'Unscrutinised applications',
          'scrutinised' => 'Scrutinised applications',
          'on_hold'     => 'Applications on hold',
          'rejected'    => 'Applications rejected',
          'in_progress' => 'Execution in progress',
          'completed'   => 'Work completed',
        );

        $consituencies = Constituency::get(['id','constituency']);
        foreach($consituencies as $consituency){
            $data = array(
                'constituency'      => $consituency->constituency,
                'total_application' => Application::where('application_type','1')->where('constituency_id',$consituency->id)->count()?: '0',
                'unscrutinised'   => Application::where('status','0')->where('application_type','1')->where('constituency_id',$consituency->id)->count()?: '0',
                'scrutinised'     => Application::where('status',1)->where('application_type','1')->where('constituency_id',$consituency->id)->count()?: '0',
                'on_hold'         => Application::where('status',3)->where('application_type','1')->where('constituency_id',$consituency->id)->count()?: '0',
                'rejected'        => Application::where('status',7)->where('application_type','1')->where('constituency_id',$consituency->id)->count()?: '0',
                'in_progress'     => Application::where('status',5)->where('application_type','1')->where('constituency_id',$consituency->id)->count()?: '0',
                'completed'       => Application::where('status',6)->where('application_type','1')->where('constituency_id',$consituency->id)->count()?: '0',
            );

            $item[] = $data;
            
        }

        //==========================================================> Repair <======================================================//

        $item[] = array(
          'application_type'  => 'Repair',
        );

        $item[] = array(
          'constituency'      => 'Constituency',
          'total_application' => 'Total Applications Recieved',
          'unscrutinised'     => 'Unscrutinised applications',
          'scrutinised' => 'Scrutinised applications',
          'on_hold'     => 'Applications on hold',
          'rejected'    => 'Applications rejected',
          'in_progress' => 'Execution in progress',
          'completed'   => 'Work completed',
        );
        foreach($consituencies as $consituency){
            $data = array(
                'constituency'      => $consituency->constituency,
                'total_application' => Application::where('application_type','2')->where('constituency_id',$consituency->id)->count()?: '0',
                'unscrutinised'   => Application::where('status','0')->where('application_type','2')->where('constituency_id',$consituency->id)->count()?: '0',
                'scrutinised'     => Application::where('status',1)->where('application_type','2')->where('constituency_id',$consituency->id)->count()?: '0',
                'on_hold'         => Application::where('status',3)->where('application_type','2')->where('constituency_id',$consituency->id)->count()?: '0',
                'rejected'        => Application::where('status',7)->where('application_type','2')->where('constituency_id',$consituency->id)->count()?: '0',
                'in_progress'     => Application::where('status',5)->where('application_type','2')->where('constituency_id',$consituency->id)->count()?: '0',
                'completed'       => Application::where('status',6)->where('application_type','2')->where('constituency_id',$consituency->id)->count()?: '0',
            );

            $item[] = $data;
            
        }

        //==========================================================> Community complex <======================================================//

        $item[] = array(
          'application_type'  => 'Community complex',
        );

        $item[] = array(
          'constituency'      => 'Constituency',
          'total_application' => 'Total Applications Recieved',
          'unscrutinised'     => 'Unscrutinised applications',
          'scrutinised' => 'Scrutinised applications',
          'on_hold'     => 'Applications on hold',
          'rejected'    => 'Applications rejected',
          'in_progress' => 'Execution in progress',
          'completed'   => 'Work completed',
        );
        foreach($consituencies as $consituency){
            $data = array(
                'constituency'      => $consituency->constituency,
                'total_application' => Application::where('application_type','3')->where('constituency_id',$consituency->id)->count()?: '0',
                'unscrutinised'   => Application::where('status','0')->where('application_type','3')->where('constituency_id',$consituency->id)->count()?: '0',
                'scrutinised'     => Application::where('status',1)->where('application_type','3')->where('constituency_id',$consituency->id)->count()?: '0',
                'on_hold'         => Application::where('status',3)->where('application_type','3')->where('constituency_id',$consituency->id)->count()?: '0',
                'rejected'        => Application::where('status',7)->where('application_type','3')->where('constituency_id',$consituency->id)->count()?: '0',
                'in_progress'     => Application::where('status',5)->where('application_type','3')->where('constituency_id',$consituency->id)->count()?: '0',
                'completed'       => Application::where('status',6)->where('application_type','3')->where('constituency_id',$consituency->id)->count()?: '0',
            );

            $item[] = $data;
            
        }

        return collect($item);
    }
}
