<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverReturn extends Model
{
    protected $table = 'driver_returns';
    protected $fillable = [
        'warehouse_id',
        'driver_id',
        'return_date',
        'product_id',
        'cases',
        'bottles',
        'is_carry_forward',
        'is_return',
        'notes',
        'created_at',
        'updated_at'
    ];
}
