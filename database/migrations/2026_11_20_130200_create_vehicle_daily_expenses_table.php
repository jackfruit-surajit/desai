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
        if (!Schema::hasTable('vehicle_daily_expenses')) {
            Schema::create('vehicle_daily_expenses', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('vehicle_id');
                $table->integer('driver_id');
                $table->date('expense_date');
                $table->string('expense_category', 50); // Toll_Tax, Parking, RTO_Fine_Challan, Driver_Food_Allowance, Helper_Allowance, Puncture_Tyre_Air, Vehicle_Washing, Other
                $table->decimal('amount', 10, 2);
                $table->string('bill_receipt_image', 255)->nullable();
                $table->string('description', 500)->nullable();
                $table->string('reimbursement_status', 30)->default('Pending'); // Pending, Approved, Settled_From_Cash, Rejected
                $table->integer('approved_by')->nullable();
                $table->timestamps();

                $table->index('vehicle_id');
                $table->index('driver_id');
                $table->index('expense_date');
                $table->index('expense_category');
                $table->index('reimbursement_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_daily_expenses');
    }
};
