<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturPembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retur_pembelian', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('nomor');
            $table->string('faktur_pembelian_kode')->unique();
            $table->dateTime('waktu_pengembalian');
            $table->timestamps();

            $table->foreign('faktur_pembelian_kode')
                ->references('kode')
                ->on('faktur_pembelian');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retur_pembelian');
    }
}
