<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model

{   protected $table = 'products';
    protected $fillable = ['name','per_case_quantity','per_case_price','per_bottle_price','cgst','sgst','hsn_code','status','updated_at','created_at',];
}
