<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierIdToUsersTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('users', 'supplier_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('supplier_id')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'supplier_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('supplier_id');
            });
        }
    }
}
