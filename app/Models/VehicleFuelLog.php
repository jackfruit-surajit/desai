<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleFuelLog extends Model
{
    protected $table = 'vehicle_fuel_logs';

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'fuel_date',
        'fuel_type',
        'quantity_liters',
        'rate_per_liter',
        'total_amount',
        'odometer_reading',
        'distance_travelled',
        'calculated_mileage',
        'fuel_station_name',
        'slip_image',
        'payment_mode',
        'approval_status',
        'approved_by',
        'remarks',
        'created_at',
        'updated_at',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function driver()
    {
        return $this->belongsTo(Admin::class, 'driver_id');
    }

    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }
}
