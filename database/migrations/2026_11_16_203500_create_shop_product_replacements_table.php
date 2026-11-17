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
                $table->integer('warehouse_id')->nullable();
                $table->date('replace_date')->nullable();
                $table->integer('total_products')->default(1);
                $table->integer('total_cases')->default(0);
                $table->integer('total_bottles')->default(0);
                // First / single product reference for direct queries
                $table->integer('product_id')->nullable();
                $table->integer('cases')->default(0);
                $table->integer('bottles')->default(0);
                $table->integer('replacement_product_id')->nullable();
                $table->integer('replacement_cases')->default(0);
                $table->integer('replacement_bottles')->default(0);
                $table->string('reason', 255)->nullable();
                $table->string('status', 50)->default('pending'); // pending, approved, replaced, completed, rejected
                $table->string('image', 555)->nullable(); // proof photo
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('shop_product_replacement_details')) {
            Schema::create('shop_product_replacement_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('replacement_id');
                $table->integer('shop_id')->nullable();
                $table->integer('driver_id')->nullable();
                $table->integer('product_id')->nullable(); // Damaged / returned product
                $table->integer('cases')->default(0);
                $table->integer('bottles')->default(0);
                $table->integer('replacement_product_id')->nullable(); // Replacement product given
                $table->integer('replacement_cases')->default(0);
                $table->integer('replacement_bottles')->default(0);
                $table->string('reason', 255)->nullable();
                $table->string('status', 50)->default('pending');
                $table->string('image', 555)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('replacement_id')->references('id')->on('shop_product_replacements')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_product_replacement_details');
        Schema::dropIfExists('shop_product_replacements');
    }
};
