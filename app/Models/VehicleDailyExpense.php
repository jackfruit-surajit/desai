<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleDailyExpense extends Model
{
    protected $table = 'vehicle_daily_expenses';

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'expense_date',
        'expense_category',
        'amount',
        'bill_receipt_image',
        'description',
        'reimbursement_status',
        'approved_by',
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
