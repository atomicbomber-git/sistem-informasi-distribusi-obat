<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_stock', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_faktur_pembelian_id')->index()->nullable();
            $table->decimal('jumlah');
            $table->unsignedInteger('stock_id')->index();
            $table->foreign('item_faktur_pembelian_id')->references('id')->on('item_faktur_pembelian');
            $table->foreign('stock_id')->references('id')->on('stock');
            $table->string('tipe')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_stock');
    }
}
