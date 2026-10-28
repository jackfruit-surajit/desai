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
        Schema::table('vehicle', function (Blueprint $table) {
            $table->string('chassis_no', 255)->nullable()->after('fuel_type');
            $table->string('engine_no', 255)->nullable()->after('chassis_no');
            $table->string('gps_imei_no', 255)->nullable()->after('engine_no');
            $table->string('pollution', 255)->nullable()->after('gps_imei_no');
            $table->string('fitness', 255)->nullable()->after('pollution');
            $table->string('puc', 255)->nullable()->after('fitness');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle', function (Blueprint $table) {
            $table->dropColumn([
                'chassis_no',
                'engine_no',
                'gps_imei_no',
                'pollution',
                'fitness',
                'puc',
            ]);
        });
    }
};

