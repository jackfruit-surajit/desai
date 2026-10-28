<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicle';

    protected $fillable = [
        'vehicle_name',
        'vehicle_no',
        'fuel_type',
        'chassis_no',
        'engine_no',
        'gps_imei_no',
        'pollution',
        'fitness',
        'puc',
        'status',
        'updated_at',
        'created_at',
    ];

    /**
     * Accessor for chassi_no to support both spellings.
     */
    public function getChassiNoAttribute()
    {
        return $this->attributes['chassis_no'] ?? null;
    }

    /**
     * Mutator for chassi_no to support both spellings.
     */
    public function setChassiNoAttribute($value)
    {
        $this->attributes['chassis_no'] = $value;
    }
}
