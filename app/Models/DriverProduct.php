<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverProduct extends Model
{
    protected $table ='driver_products';
    protected $fillable = ['driver_id','warehouse_id','total_products','grand_total','updated_at','created_at'];

}
