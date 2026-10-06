<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverAttendanceRouteLog extends Model
{
    protected $table ='driver_attendance_route_logs';
    protected $fillable = ['driver_id','route_id','updated_at','created_at'];

}
