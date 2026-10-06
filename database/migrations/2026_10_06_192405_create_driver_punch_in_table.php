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
        Schema::create('driver_punch_in', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('selfie', 555);
            $table->dateTime('punch_in_time')->nullable();
            $table->dateTime('break_time')->nullable();
            $table->dateTime('punch_out_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_punch_in');
    }
};
