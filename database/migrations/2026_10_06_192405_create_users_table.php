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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->enum('email_verified', ['0', '1'])->default('0');
            $table->string('email', 555);
            $table->integer('phone_verified')->default(0);
            $table->string('email_otp', 10)->nullable();
            $table->string('phone_otp', 10)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->text('image')->nullable();
            $table->rememberToken();
            $table->text('activation_token')->nullable();
            $table->enum('status', ['0', '1', '2', '3'])->default('0');
            $table->dateTime('last_login')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
