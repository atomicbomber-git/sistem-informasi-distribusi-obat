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

            $table->string('produk_kode')->index()->comment('Kode produk.');
            $table->decimal('jumlah');
            $table->decimal('nilai_satuan');

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
        Schema::dropIfExists('stock_batch');
    }
}
