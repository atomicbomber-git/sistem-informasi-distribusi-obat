<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemFakturPembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_faktur_pembelian', function (Blueprint $table) {
            $table->increments('id');
            $table->string('produk_kode')->index();
            $table->decimal('jumlah');
            $table->decimal('harga_satuan');
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
        Schema::dropIfExists('item_faktur_pembelian');
    }
}
