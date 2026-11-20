<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicle';

    protected $fillable = [
        'vehicle_name',
        'vehicle_no',
        'current_odometer',
        'fuel_type',
        'fuel_tank_capacity',
        'chassis_no',
        'engine_no',
        'gps_imei_no',
        'pollution',
        'fitness',
        'puc',
        'insurance_expiry',
        'fitness_expiry',
        'puc_expiry',
        'status',
        'assigned_driver_id',
        'updated_at',
        'created_at',
    ];

    public function fuelLogs()
    {
        return $this->hasMany(VehicleFuelLog::class, 'vehicle_id');
    }

    public function dailyExpenses()
    {
        return $this->hasMany(VehicleDailyExpense::class, 'vehicle_id');
    }

    public function maintenanceSchedules()
    {
        return $this->hasMany(VehicleMaintenanceSchedule::class, 'vehicle_id');
    }

    public function repairLogs()
    {
        return $this->hasMany(VehicleRepairLog::class, 'vehicle_id');
    }

    public function assignedDriver()
    {
        return $this->belongsTo(Admin::class, 'assigned_driver_id');
    }

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
