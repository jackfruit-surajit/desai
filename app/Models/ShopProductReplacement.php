<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProductReplacement extends Model
{
    protected $table = 'shop_product_replacements';

    protected $fillable = [
        'replacement_no',
        'shop_id',
        'driver_id',
        'replace_date',
        'product_id',
        'cases',
        'bottles',
        'reason',
        'status',
        'created_at',
        'updated_at'
    ];

  
}
