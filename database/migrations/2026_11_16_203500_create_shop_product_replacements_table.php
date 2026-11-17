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
        if (!Schema::hasTable('shop_product_replacements')) {
            Schema::create('shop_product_replacements', function (Blueprint $table) {
                $table->id();
                $table->string('replacement_no', 100)->nullable()->unique();
                $table->integer('shop_id');
                $table->integer('driver_id');
                $table->date('replace_date')->nullable();
                $table->integer('product_id')->nullable();
                $table->integer('cases')->default(0);
                $table->integer('bottles')->default(0);
                $table->string('reason', 255)->nullable();
                $table->string('status', 50)->default('0'); // pending, approved, replaced, completed, rejected
                $table->string('image', 555)->nullable(); // proof photo
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_product_replacements');
    }
};
