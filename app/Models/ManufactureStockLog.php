<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManufactureStockLog extends Model
{
    protected $table = 'manufacture_stock_logs';
    
    protected $fillable = [
        'manufacture_id',
        'product_id',
        'type', // production, dispatch, adjustment
        'quantity',
        'warehouse_id',
        'batch_no',
        'note',
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

    public function warehouse()
    {
        return $this->belongsTo(Admin::class, 'warehouse_id', 'id');
    }
}

