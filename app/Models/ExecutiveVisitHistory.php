<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExecutiveVisitHistory extends Model

{   protected $table = 'sales_visit_history';
    protected $fillable = ['shop_id','executive_id','status','customer_note','shop_requirement','shop_photo','updated_at','created_at','skip_reason'];
}
