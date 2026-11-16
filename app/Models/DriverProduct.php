<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverProduct extends Model
{
    protected $table ='driver_products';
    protected $fillable = ['driver_id','warehouse_id','total_products','grand_total','assign_date','updated_at','created_at'];

}
