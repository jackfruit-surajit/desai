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
        Schema::create('invoice_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('shop_id')->nullable();
            $table->string('invoice_id', 555)->nullable();
            $table->integer('driver_id');
            $table->longText('file_name');
            $table->longText('path')->nullable();
            $table->string('invoice_grand_total', 555)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_history');
    }
};
