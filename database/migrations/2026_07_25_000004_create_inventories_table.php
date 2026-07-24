<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('on_hand_qty')->default(0);
            $table->unsignedInteger('incoming_qty')->default(0); // units on open/ordered POs
            $table->unsignedInteger('reorder_point')->default(0); // configurable low-stock threshold
            $table->timestamp('last_updated_at')->nullable(); // set on each goods receipt
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
