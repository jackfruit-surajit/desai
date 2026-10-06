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
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 100)->index('slug');
            $table->string('title', 100);
            $table->text('description');
            $table->set('type', ['text', 'textarea', 'password', 'select', 'select-multiple', 'radio', 'checkbox', 'file']);
            $table->text('default');
            $table->text('value')->nullable();
            $table->text('options');
            $table->integer('is_required');
            $table->integer('is_gui');
            $table->string('module', 50);
            $table->integer('row_order')->default(0);

            $table->unique(['slug'], 'unique_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
