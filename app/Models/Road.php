<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Road extends Model

{   protected $table = 'road';
    protected $fillable = ['area_id','road_name','full_address','status','updated_at','created_at',];
}
