<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemReturPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_retur_penjualan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mutasi_stock_penjualan_id')->index();
            $table->string('jumlah');
            $table->string('alasan');
            $table->timestamps();
            $table->foreign('mutasi_stock_penjualan_id')->references('id')->on('mutasi_stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_retur_penjualan');
    }
}
