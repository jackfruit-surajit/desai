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
        Schema::create('driver_products', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('driver_id');
            $table->integer('warehouse_id');
            $table->integer('total_products');
            $table->string('grand_total', 555)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_products');
    }
};
