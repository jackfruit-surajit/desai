<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table ='services';
    protected $fillable = ['service_name','category','price','description','duration','image','status','updated_at','created_at'];
}
