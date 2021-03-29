<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFakturPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faktur_penjualan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pelanggan_id')->index();
            $table->integer('nomor');
            $table->decimal('diskon');
            $table->decimal('pajak');
            $table->dateTime('waktu_pengeluaran');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan');
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
        Schema::dropIfExists('faktur_penjualan');
    }
}
