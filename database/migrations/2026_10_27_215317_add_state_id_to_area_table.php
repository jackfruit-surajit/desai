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
        if (Schema::hasTable('area') && !Schema::hasColumn('area', 'state_id')) {
            Schema::table('area', function (Blueprint $table) {
                $table->integer('state_id')->nullable()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('area') && Schema::hasColumn('area', 'state_id')) {
            Schema::table('area', function (Blueprint $table) {
                $table->dropColumn('state_id');
            });
        }
    }
};
