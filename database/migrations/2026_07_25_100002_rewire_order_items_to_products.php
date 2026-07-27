<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['inventory_item_id']);
            $table->dropColumn('inventory_item_id');

            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');

            $table->foreignId('product_id')->after('order_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('users')->cascadeOnDelete();
        });
    }
};
