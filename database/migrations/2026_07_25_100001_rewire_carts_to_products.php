<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign('carts_customer_id_foreign');
            $table->dropForeign('carts_inventory_item_id_foreign');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique('carts_customer_id_inventory_item_id_unique');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('inventory_item_id');
            $table->foreign('customer_id')->references('id')->on('users')->cascadeOnDelete();
            
            $table->foreignId('product_id')->after('customer_id')->constrained()->cascadeOnDelete();
            $table->unique(['customer_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique(['customer_id', 'product_id']);
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->unique(['customer_id', 'inventory_item_id']);
        });
    }
};
