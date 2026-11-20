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
        if (!Schema::hasTable('vehicle_fuel_logs')) {
            Schema::create('vehicle_fuel_logs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('vehicle_id');
                $table->integer('driver_id');
                $table->date('fuel_date');
                $table->string('fuel_type', 50)->default('Diesel');
                $table->decimal('quantity_liters', 8, 2);
                $table->decimal('rate_per_liter', 8, 2);
                $table->decimal('total_amount', 10, 2);
                $table->unsignedInteger('odometer_reading')->nullable();
                $table->unsignedInteger('distance_travelled')->nullable()->default(0)->comment('KM run since previous fuel entry');
                $table->decimal('calculated_mileage', 6, 2)->nullable()->comment('KM per Liter');
                $table->string('fuel_station_name', 255)->nullable();
                $table->string('slip_image', 255)->nullable();
                $table->string('payment_mode', 50)->default('Cash'); // Cash, Fastag, Company_Card, Online_UPI, Credit_Account
                $table->string('approval_status', 30)->default('Pending'); // Pending, Approved, Rejected
                $table->integer('approved_by')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->index('vehicle_id');
                $table->index('driver_id');
                $table->index('fuel_date');
                $table->index('approval_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_fuel_logs');
    }
};
