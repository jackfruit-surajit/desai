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
        if (Schema::hasTable('driver_returns')) {
            Schema::table('driver_returns', function (Blueprint $table) {
                if (!Schema::hasColumn('driver_returns', 'is_carry_forward')) {
                    $table->boolean('is_carry_forward')->default(0)->after('bottles');
                }
                if (!Schema::hasColumn('driver_returns', 'is_return')) {
                    $table->boolean('is_return')->default(0)->after('is_carry_forward');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('driver_returns')) {
            Schema::table('driver_returns', function (Blueprint $table) {
                if (Schema::hasColumn('driver_returns', 'is_carry_forward')) {
                    $table->dropColumn('is_carry_forward');
                }
                if (Schema::hasColumn('driver_returns', 'is_return')) {
                    $table->dropColumn('is_return');
                }
            });
        }
    }
};
