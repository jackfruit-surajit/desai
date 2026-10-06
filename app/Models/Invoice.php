<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model

{   protected $table = 'invoice';
    protected $fillable = ['invoice_no','driver_id','shop_id','total_price','updated_at','created_at',];
}
