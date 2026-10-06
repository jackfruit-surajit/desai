<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesExecutiveArea extends Model

{   protected $table = 'sales_executive_area';
    protected $fillable = ['sales_executive_id','area_id','updated_at','created_at',];
}
