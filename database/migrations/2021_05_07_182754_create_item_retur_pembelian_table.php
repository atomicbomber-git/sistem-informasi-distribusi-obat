<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemReturPembelianTable extends Migration
{
    public function up()
    {
        Schema::create('item_retur_pembelian', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_faktur_pembelian_id')->index();
            $table->string('jumlah');
            $table->string('alasan');
            $table->timestamps();
            $table->foreign('item_faktur_pembelian_id')->references('id')->on('item_faktur_pembelian');
        });
    }

    public function down()
    {
        Schema::dropIfExists('item_retur_pembelian');
    }
}