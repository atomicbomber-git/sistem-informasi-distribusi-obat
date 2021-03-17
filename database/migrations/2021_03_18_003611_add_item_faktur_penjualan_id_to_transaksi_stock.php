<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemFakturPenjualanIdToTransaksiStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi_stock', function (Blueprint $table) {
            $table->unsignedInteger('item_faktur_penjualan_id')
                ->after('item_faktur_pembelian_id')
                ->index()
                ->nullable();

            $table->foreign('item_faktur_penjualan_id')
                ->references('id')
                ->on('item_faktur_penjualan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_stock', function (Blueprint $table) {
            $table->dropForeign(['item_faktur_penjualan_id']);
            $table->dropColumn('item_faktur_penjualan_id');
        });
    }
}
