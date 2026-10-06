<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseInvoiceDate extends Model

{   protected $table = 'warehouse_invoice_data';
    protected $fillable = ['warehouse_invoice_id','product_id','quantity','unit','updated_at','created_at'];
}
