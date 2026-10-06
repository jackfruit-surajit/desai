<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseProduct extends Model
{
    protected $table ='warehouse_products';
    protected $fillable = ['warehouse_id','product_id','per_case_quantity','comment','status','updated_at','created_at'];

}
