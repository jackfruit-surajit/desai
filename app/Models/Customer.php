<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table ='customers';
    protected $fillable = [//'password','gender','photo','state',
                           'name','email','mobile_no','shop_name','shop_photo','gst_no','latitude','longitude','area_id','road_id','address','pan_no','landmark','state','city','pin_code',
                           'status','sequence','created_by','updated_at','created_at'];

}
