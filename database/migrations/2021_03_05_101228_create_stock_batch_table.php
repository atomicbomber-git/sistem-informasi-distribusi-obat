<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_batch', function (Blueprint $table) {
            $table->string('kode_batch')->primary();
            $table->unsignedInteger('item_faktur_pembelian_id')->index()->nullable();
            $table->string('produk_kode')->index()->comment('Kode produk.');
            $table->decimal('jumlah');
            $table->decimal('nilai_satuan', 19, 4);
            $table->dateTime("expired_at")->index();
            $table->timestamps();
            $table->foreign('produk_kode')->references('kode')->on('produk');
            $table->foreign('item_faktur_pembelian_id')->references('id')->on('item_faktur_pembelian');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_batch');
    }
}
