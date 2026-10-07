<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesExecutiveAreaVisitLog extends Model
{
    protected $table = 'sales_executive_area_visit_logs';
    protected $fillable = ['sales_executive_id','area_id','updated_at','created_at',];
}
