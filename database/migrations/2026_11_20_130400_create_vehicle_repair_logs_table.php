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
        if (!Schema::hasTable('vehicle_repair_logs')) {
            Schema::create('vehicle_repair_logs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('vehicle_id');
                $table->integer('driver_id')->nullable();
                $table->date('repair_date');
                $table->date('completion_date')->nullable();
                $table->string('garage_workshop_name', 255);
                $table->string('garage_contact_no', 50)->nullable();
                $table->string('invoice_number', 100)->nullable();
                $table->string('repair_reason', 50); // Breakdown, Accident, Normal_Wear_Tear, Preventive_Service
                $table->text('issues_reported')->nullable();
                $table->text('work_performed')->nullable();
                $table->decimal('spare_parts_cost', 10, 2)->default(0.00);
                $table->decimal('labor_charges', 10, 2)->default(0.00);
                $table->decimal('tax_amount', 10, 2)->default(0.00);
                $table->decimal('total_cost', 10, 2);
                $table->unsignedInteger('odometer_at_repair')->nullable();
                $table->string('bill_copy_image', 255)->nullable();
                $table->string('payment_status', 30)->default('Paid'); // Paid, Unpaid, Credit
                $table->string('payment_mode', 50)->nullable();
                $table->timestamps();

                $table->index('vehicle_id');
                $table->index('driver_id');
                $table->index('repair_date');
                $table->index('repair_reason');
                $table->index('payment_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_repair_logs');
    }
};
