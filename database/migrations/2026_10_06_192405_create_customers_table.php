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
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('first_name', 555)->nullable();
            $table->string('last_name', 555)->nullable();
            $table->string('email', 555)->nullable();
            $table->string('password', 555)->nullable();
            $table->string('gender', 555)->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('photo')->nullable();
            $table->string('state')->nullable();
            $table->string('city', 555)->nullable();
            $table->string('pin_code', 555)->nullable();
            $table->longText('landmark')->nullable();
            $table->string('address', 555)->nullable();
            $table->string('shop_name', 555)->nullable();
            $table->string('shop_photo', 555)->nullable();
            $table->string('gst_no', 555)->nullable();
            $table->string('pan_no', 555)->nullable();
            $table->string('latitude', 555)->nullable();
            $table->string('longitude', 555)->nullable();
            $table->integer('area_id')->nullable();
            $table->integer('road_id')->nullable();
            $table->string('status')->default('1');
            $table->integer('sequence')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
