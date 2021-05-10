<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemReturPembelianIdToMutasiStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mutasi_stock', function (Blueprint $table) {
            $table->unsignedInteger('item_retur_pembelian_id')
                ->after('item_retur_penjualan_id')
                ->nullable()
                ->index();
            $table->foreign('item_retur_pembelian_id')->references('id')->on('item_retur_pembelian');
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
            $table->dropForeign('item_retur_pembelian_Id');
        });
    }
}
