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
        Schema::create('warehouse_invoice', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('warehouse_id');
            $table->string('shop_name', 555);
            $table->string('contact_no', 555);
            $table->string('email', 555);
            $table->timestamps();
            $table->string('grand_total', 555)->nullable();
            $table->string('file_name', 555)->nullable();
            $table->longText('path')->nullable();
            $table->longText('address')->nullable();
            $table->string('gst_no', 555)->nullable();
            $table->string('pan_no', 555)->nullable();
            $table->string('state', 555)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_invoice');
    }
};
