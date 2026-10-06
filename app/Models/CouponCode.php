<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    protected $table ='coupon_codes';
    protected $fillable = ['coupon_code','discount_amount','percentage_discount','title','status','updated_at','created_at'];

}
