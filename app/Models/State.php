<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    
    protected $fillable = [
        'state_name',
        'state_code',
        'status',
        'created_at',
        'updated_at',
    ];
}

