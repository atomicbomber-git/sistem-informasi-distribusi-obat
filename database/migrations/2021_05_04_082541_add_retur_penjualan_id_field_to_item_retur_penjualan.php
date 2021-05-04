<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturPenjualanIdFieldToItemReturPenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_retur_penjualan', function (Blueprint $table) {
            $table->unsignedInteger('retur_penjualan_id')
                ->after("id")
                ->index();

            $table->foreign('retur_penjualan_id')->references('id')->on('retur_penjualan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_retur_penjualan', function (Blueprint $table) {
            $table->dropForeign('retur_penjualan_id');
            $table->dropColumn('retur_penjualan_id');
        });
    }
}
