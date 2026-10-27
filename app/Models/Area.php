<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area';
    
    protected $fillable = [
        'state_id',
        'area_name',
        'status',
        'updated_at',
        'created_at',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }
}
