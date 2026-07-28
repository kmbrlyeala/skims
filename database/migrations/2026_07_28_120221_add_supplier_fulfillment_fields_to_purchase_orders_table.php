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
        Schema::table('purchase_orders', function (Blueprint $table) {
            // New fulfillment/rejection fields
            $table->string('reject_reason')->nullable();
            $table->string('tracking_number')->nullable();
            
            // New invoice fields
            $table->string('invoice_number')->nullable();
            $table->decimal('invoice_amount', 12, 2)->nullable();
            $table->date('invoice_due_date')->nullable();
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, overdue
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn([
                'reject_reason',
                'tracking_number',
                'invoice_number',
                'invoice_amount',
                'invoice_due_date',
                'payment_status'
            ]);
        });
    }
};
