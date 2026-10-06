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
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('type_id')->nullable();
            $table->bigInteger('role_id')->nullable();
            $table->string('name');
            $table->string('email')->unique('users_email_unique');
            $table->string('phone', 20)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('image')->nullable();
            $table->integer('area_id')->nullable();
            $table->integer('vehicle_id')->nullable();
            $table->integer('m_pin')->nullable();
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
        Schema::dropIfExists('admins');
    }
};
