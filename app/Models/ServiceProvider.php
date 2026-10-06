<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ServiceProvider extends Model
{
    protected $table ='service_providers';
    protected $fillable = ['name','service','service_category','email','contact_no','password','address','state','city','description','image','status','updated_at','created_at'];
}
