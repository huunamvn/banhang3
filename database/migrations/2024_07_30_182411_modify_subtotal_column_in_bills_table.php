<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySubtotalColumnInBillsTable extends Migration
{
    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->decimal('subtotal', 15, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->integer('subtotal')->change();
        });
    }
}
