<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemFakturPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_faktur_penjualan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('faktur_penjualan_nomor')->index();
            $table->string('produk_kode')->index();
            $table->decimal('jumlah');
            $table->decimal('harga_satuan', 19, 4);
            $table->decimal('diskon');
            $table->foreign('faktur_penjualan_nomor')->references('nomor')->on('faktur_penjualan')->cascadeOnUpdate();
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
        Schema::dropIfExists('item_faktur_penjualan');
    }
}
