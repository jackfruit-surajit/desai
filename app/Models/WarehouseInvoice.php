<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseInvoice extends Model

{   protected $table = 'warehouse_invoice';
    protected $fillable = ['warehouse_id','shop_name','contact_no','email','updated_at','created_at','grand_total','file_name','path','address','gst_no','pan_no','state',];
}
