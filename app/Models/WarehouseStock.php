<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    protected $table ='warehouse_stock';
    protected $fillable = ['warehouse_id','product_id','stock','updated_at','created_at'];

}
