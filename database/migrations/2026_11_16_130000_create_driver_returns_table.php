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
        if (!Schema::hasTable('driver_returns')) {
            Schema::create('driver_returns', function (Blueprint $table) {
                $table->id();
                $table->integer('warehouse_id');
                $table->integer('driver_id');
                $table->date('return_date');
                $table->integer('product_id')->nullable();
                $table->integer('cases')->default(0);
                $table->integer('bottles')->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_returns');
    }
};
