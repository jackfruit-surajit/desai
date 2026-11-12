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
        if (Schema::hasTable('manufacture_stock') && !Schema::hasColumn('manufacture_stock', 'bottles')) {
            Schema::table('manufacture_stock', function (Blueprint $table) {
                $table->integer('bottles')->default(0)->after('stock');
            });
        }

        if (Schema::hasTable('manufacture_stock_logs') && !Schema::hasColumn('manufacture_stock_logs', 'bottles')) {
            Schema::table('manufacture_stock_logs', function (Blueprint $table) {
                $table->integer('bottles')->default(0)->after('quantity');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('manufacture_stock') && Schema::hasColumn('manufacture_stock', 'bottles')) {
            Schema::table('manufacture_stock', function (Blueprint $table) {
                $table->dropColumn('bottles');
            });
        }

        if (Schema::hasTable('manufacture_stock_logs') && Schema::hasColumn('manufacture_stock_logs', 'bottles')) {
            Schema::table('manufacture_stock_logs', function (Blueprint $table) {
                $table->dropColumn('bottles');
            });
        }
    }
};
