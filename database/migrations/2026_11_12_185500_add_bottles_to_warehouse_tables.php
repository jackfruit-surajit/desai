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
        if (Schema::hasTable('warehouse_products') && !Schema::hasColumn('warehouse_products', 'bottles')) {
            Schema::table('warehouse_products', function (Blueprint $table) {
                $table->integer('bottles')->default(0)->after('per_case_quantity');
            });
        }

        if (Schema::hasTable('warehouse_stock') && !Schema::hasColumn('warehouse_stock', 'bottles')) {
            Schema::table('warehouse_stock', function (Blueprint $table) {
                $table->integer('bottles')->default(0)->after('stock');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('warehouse_products') && Schema::hasColumn('warehouse_products', 'bottles')) {
            Schema::table('warehouse_products', function (Blueprint $table) {
                $table->dropColumn('bottles');
            });
        }

        if (Schema::hasTable('warehouse_stock') && Schema::hasColumn('warehouse_stock', 'bottles')) {
            Schema::table('warehouse_stock', function (Blueprint $table) {
                $table->dropColumn('bottles');
            });
        }
    }
};
