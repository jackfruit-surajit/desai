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
        Schema::create('email_content', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('email_code', 250)->nullable();
            $table->text('about')->nullable();
            $table->text('subject')->nullable();
            $table->text('body')->nullable();
            $table->enum('status', ['0', '1', '3'])->nullable()->default('0');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_content');
    }
};
