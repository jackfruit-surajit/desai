<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverPunchIn extends Model
{
    protected $table ='driver_punch_in';
    protected $fillable = ['user_id','selfie','punch_in_time','break_time','punch_out_time','updated_at','created_at'];

}
