<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model

{   protected $table = 'area';
    protected $fillable = ['area_name','status','updated_at','created_at',];
}
