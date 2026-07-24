<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('po_number')->unique(); // auto-generated: PO-YYYYMMDD-XXXX
            $table->unsignedInteger('quantity_ordered');
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_cost', 12, 2);
            $table->date('expected_arrival_date');
            $table->enum('status', [
                'ordered',
                'partially_received',
                'received',
                'cancelled',
            ])->default('ordered');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
