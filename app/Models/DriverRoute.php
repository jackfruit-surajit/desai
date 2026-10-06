<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverRoute extends Model
{
    protected $table ='driver_route';
    protected $fillable = ['driver_id','road_id','serial_no','updated_at','created_at'];

}
