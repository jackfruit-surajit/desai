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
        'notes',
        'created_at',
        'updated_at'
    ];
}
