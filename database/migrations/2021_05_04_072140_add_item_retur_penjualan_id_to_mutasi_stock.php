<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemReturPenjualanIdToMutasiStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mutasi_stock', function (Blueprint $table) {
            $table->unsignedInteger('item_retur_penjualan_id')
                ->after("item_faktur_penjualan_id")
                ->nullable()
                ->index();

            $table->foreign('item_retur_penjualan_id')->references('id')->on('item_retur_penjualan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mutasi_stock', function (Blueprint $table) {
            $table->dropForeign('item_retur_penjualan_id');
            $table->dropColumn('item_retur_penjualan_id');
        });
    }
}
