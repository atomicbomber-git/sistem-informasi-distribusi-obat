<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_batch');
            $table->string('produk_kode')->comment('Kode produk.');
            $table->decimal('jumlah');
            $table->decimal('nilai_satuan', 19, 4);
            $table->string('status')->index();
            $table->dateTime("expired_at")->index();
            $table->timestamps();
            $table->foreign('produk_kode')->references('kode')->on('produk')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock');
    }
}
