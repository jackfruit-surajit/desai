<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('driver_product_details') && !Schema::hasColumn('driver_product_details', 'bottles')) {
            Schema::table('driver_product_details', function (Blueprint $table) {
                $table->integer('bottles')->default(0)->after('quantity');
            });
        }

        if (Schema::hasTable('driver_products') && !Schema::hasColumn('driver_products', 'assign_date')) {
            Schema::table('driver_products', function (Blueprint $table) {
                $table->date('assign_date')->nullable()->after('grand_total');
            });

            DB::statement('UPDATE driver_products SET assign_date = DATE(created_at) WHERE assign_date IS NULL AND created_at IS NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('driver_product_details') && Schema::hasColumn('driver_product_details', 'bottles')) {
            Schema::table('driver_product_details', function (Blueprint $table) {
                $table->dropColumn('bottles');
            });
        }

        if (Schema::hasTable('driver_products') && Schema::hasColumn('driver_products', 'assign_date')) {
            Schema::table('driver_products', function (Blueprint $table) {
                $table->dropColumn('assign_date');
            });
        }
    }
};
