<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManufactureStock extends Model
{
    protected $table = 'manufacture_stock';
    
    protected $fillable = [
        'manufacture_id',
        'product_id',
        'stock',
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Admin::class, 'manufacture_id', 'id');
    }
}

