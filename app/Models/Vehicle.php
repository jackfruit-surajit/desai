<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model

{   protected $table = 'vehicle';
    protected $fillable = ['vehicle_name','vehicle_no','fuel_type','status','updated_at','created_at',];
}
