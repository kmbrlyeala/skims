<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity_received');
            $table->unsignedInteger('quantity_damaged')->default(0);
            $table->foreignId('received_by')->constrained('users');
            $table->date('received_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receipts');
    }
};
