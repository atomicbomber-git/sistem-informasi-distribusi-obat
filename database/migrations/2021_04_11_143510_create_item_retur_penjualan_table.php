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
            $table->string('produk_kode')->index();
            $table->string('kode_batch');
            $table->string('jumlah');
            $table->string('alasan');
            $table->timestamps();
            $table->foreign('produk_kode')->references('kode')->on('produk');
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
