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
        if (!Schema::hasTable('manufacture_stock')) {
            Schema::create('manufacture_stock', function (Blueprint $table) {
                $table->id();
                $table->integer('manufacture_id')->default(0);
                $table->integer('product_id');
                $table->integer('stock')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('manufacture_stock_logs')) {
            Schema::create('manufacture_stock_logs', function (Blueprint $table) {
                $table->id();
                $table->integer('manufacture_id')->default(0);
                $table->integer('product_id');
                $table->string('type', 50)->default('production'); // production, dispatch, adjustment
                $table->integer('quantity')->default(0);
                $table->integer('warehouse_id')->nullable();
                $table->string('batch_no', 100)->nullable();
                $table->text('note')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacture_stock_logs');
        Schema::dropIfExists('manufacture_stock');
    }
};
