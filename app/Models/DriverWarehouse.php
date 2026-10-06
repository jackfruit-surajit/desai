<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverWarehouse extends Model
{
    protected $table ='driver_warehouse';
    protected $fillable = ['driver_id','warehouse_id','updated_at','created_at'];

}
