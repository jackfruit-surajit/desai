<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMaintenanceSchedule extends Model
{
    protected $table = 'vehicle_maintenance_schedules';

    protected $fillable = [
        'vehicle_id',
        'service_type',
        'interval_km',
        'interval_months',
        'last_service_date',
        'last_service_odometer',
        'next_due_date',
        'next_due_odometer',
        'alert_advance_days',
        'alert_advance_km',
        'status',
        'created_at',
        'updated_at',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
