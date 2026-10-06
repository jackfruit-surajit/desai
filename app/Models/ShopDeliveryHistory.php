<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopDeliveryHistory extends Model
{
    protected $table ='shop_delivery_history';
    protected $fillable = ['shop_id','driver_id','delivery_date','delivery_status','selfie','delivery_note','updated_at','created_at'];

}
