<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retur_penjualan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('faktur_penjualan_id')->index();
            $table->dateTime('waktu_pengembalian');
            $table->timestamps();
            $table->foreign('faktur_penjualan_id')->references('id')->on('faktur_penjualan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retur_penjualan');
    }
}
