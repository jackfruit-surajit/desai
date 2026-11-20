<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('vehicle_maintenance_schedules')) {
            Schema::create('vehicle_maintenance_schedules', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('vehicle_id');
                $table->string('service_type', 50); // Engine_Oil_Filter, Brake_Service, Tyre_Replacement_Rotation, Battery_Electrical, Suspension_Greasing, AC_Coolant_Service, Full_Vehicle_Overhaul
                $table->unsignedInteger('interval_km')->default(10000)->comment('Every N kilometers');
                $table->unsignedInteger('interval_months')->default(6)->comment('Every N months');
                $table->date('last_service_date')->nullable();
                $table->unsignedInteger('last_service_odometer')->default(0);
                $table->date('next_due_date')->nullable();
                $table->unsignedInteger('next_due_odometer')->nullable();
                $table->integer('alert_advance_days')->default(7);
                $table->integer('alert_advance_km')->default(500);
                $table->string('status', 30)->default('Normal'); // Normal, Due_Soon, Overdue
                $table->timestamps();

                $table->index('vehicle_id');
                $table->index('service_type');
                $table->index('status');
                $table->index('next_due_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenance_schedules');
    }
};
