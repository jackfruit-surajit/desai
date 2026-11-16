<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverProductDetails extends Model
{
    protected $table ='driver_product_details';
    protected $fillable = ['driver_products_id','driver_id','product_id','quantity','bottles','updated_at','created_at'];

}