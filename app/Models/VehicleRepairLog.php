<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleRepairLog extends Model
{
    protected $table = 'vehicle_repair_logs';

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'repair_date',
        'completion_date',
        'garage_workshop_name',
        'garage_contact_no',
        'invoice_number',
        'repair_reason',
        'issues_reported',
        'work_performed',
        'spare_parts_cost',
        'labor_charges',
        'tax_amount',
        'total_cost',
        'odometer_at_repair',
        'bill_copy_image',
        'payment_status',
        'payment_mode',
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
}
