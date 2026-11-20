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
        if (Schema::hasTable('vehicle')) {
            Schema::table('vehicle', function (Blueprint $table) {
                if (!Schema::hasColumn('vehicle', 'current_odometer')) {
                    $table->unsignedInteger('current_odometer')->default(0)->after('vehicle_no');
                }
                if (!Schema::hasColumn('vehicle', 'fuel_tank_capacity')) {
                    $table->decimal('fuel_tank_capacity', 8, 2)->nullable()->after('fuel_type');
                }
                if (!Schema::hasColumn('vehicle', 'assigned_driver_id')) {
                    $table->integer('assigned_driver_id')->nullable()->after('status');
                }
                if (!Schema::hasColumn('vehicle', 'insurance_expiry')) {
                    $table->date('insurance_expiry')->nullable()->after('puc');
                }
                if (!Schema::hasColumn('vehicle', 'fitness_expiry')) {
                    $table->date('fitness_expiry')->nullable()->after('insurance_expiry');
                }
                if (!Schema::hasColumn('vehicle', 'puc_expiry')) {
                    $table->date('puc_expiry')->nullable()->after('fitness_expiry');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('vehicle')) {
            Schema::table('vehicle', function (Blueprint $table) {
                $columnsToDrop = [];
                foreach (['current_odometer', 'fuel_tank_capacity', 'assigned_driver_id', 'insurance_expiry', 'fitness_expiry', 'puc_expiry'] as $col) {
                    if (Schema::hasColumn('vehicle', $col)) {
                        $columnsToDrop[] = $col;
                    }
                }
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};
