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
        if (Schema::hasTable('warehouse_products')) {
            Schema::table('warehouse_products', function (Blueprint $table) {
                if (!Schema::hasColumn('warehouse_products', 'assign_date')) {
                    $table->date('assign_date')->nullable()->after('comment');
                }
                if (!Schema::hasColumn('warehouse_products', 'accept_date')) {
                    $table->date('accept_date')->nullable()->after('assign_date');
                }
            });

            // Backfill existing records cleanly
            DB::statement('UPDATE warehouse_products SET assign_date = DATE(created_at) WHERE assign_date IS NULL AND created_at IS NOT NULL');
            DB::statement('UPDATE warehouse_products SET accept_date = DATE(updated_at) WHERE accept_date IS NULL AND status = 1 AND updated_at IS NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('warehouse_products')) {
            Schema::table('warehouse_products', function (Blueprint $table) {
                if (Schema::hasColumn('warehouse_products', 'accept_date')) {
                    $table->dropColumn('accept_date');
                }
                if (Schema::hasColumn('warehouse_products', 'assign_date')) {
                    $table->dropColumn('assign_date');
                }
            });
        }
    }
};
