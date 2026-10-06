<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesExecutiveRoute extends Model

{   protected $table = 'sales_executive_route';
    protected $fillable = ['sales_executive_id','area_id','route_id','updated_at','created_at',];
}
