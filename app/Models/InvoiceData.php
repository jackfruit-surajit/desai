<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceData extends Model

{   protected $table = 'invoice_data';
    protected $fillable = ['invoice_id','product_id','quantity','updated_at','created_at',];
}
