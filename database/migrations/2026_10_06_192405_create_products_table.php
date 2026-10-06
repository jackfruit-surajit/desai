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
        Schema::create('products', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 555);
            $table->string('hsn_code', 555)->nullable();
            $table->integer('per_case_quantity')->nullable();
            $table->string('per_case_price', 555);
            $table->string('per_bottle_price', 555);
            $table->string('cgst', 555)->nullable();
            $table->string('sgst', 555)->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
