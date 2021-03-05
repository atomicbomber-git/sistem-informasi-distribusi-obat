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
            $table->string('faktur_pembelian_kode')->index();
            $table->string('produk_kode')->index();
            $table->decimal('jumlah');
            $table->decimal('harga_satuan', 19, 4);
            $table->timestamps();
            $table->foreign('produk_kode')->references('kode')->on('produk');
            $table->foreign('faktur_pembelian_kode')->references('kode')->on('faktur_pembelian');
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
