<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceHistory extends Model

{   protected $table = 'invoice_history';
    protected $fillable = ['shop_id','invoice_id','driver_id','invoice_grand_total','file_name','path','updated_at','created_at',];
}
