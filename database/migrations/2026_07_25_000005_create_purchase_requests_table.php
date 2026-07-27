<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity_requested');
            $table->decimal('unit_cost', 10, 2);
            $table->date('expected_delivery_date')->nullable();
            $table->enum('status', [
                'draft',
                'pending_approval',
                'pending_factory_approval',
                'approved',
                'rejected',
                'received',
                'partially_received',
            ])->default('pending_approval');
            $table->text('notes')->nullable();
            $table->boolean('is_auto_draft')->default(false); // true = system-generated reorder
            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
