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
        Schema::create('shop_delivery_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('shop_id');
            $table->integer('driver_id');
            $table->date('delivery_date');
            $table->integer('delivery_status')->default(0);
            $table->string('selfie', 555)->nullable();
            $table->longText('delivery_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_delivery_history');
    }
};
