<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_stock', function (Blueprint $table) {
            $table->id();
            $table->string('stock_kode_batch')->index();
            $table->decimal('jumlah');

            $table->foreign('stock_kode_batch')
                ->references('kode_batch')
                ->on('stock_batch');

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
        Schema::dropIfExists('transaksi_stock');
    }
}
