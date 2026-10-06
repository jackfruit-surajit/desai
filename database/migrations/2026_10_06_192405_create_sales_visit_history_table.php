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
        Schema::create('sales_visit_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('shop_id');
            $table->integer('executive_id');
            $table->integer('status')->default(0);
            $table->string('shop_photo', 555)->nullable();
            $table->longText('customer_note');
            $table->longText('shop_requirement')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->longText('skip_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_visit_history');
    }
};
