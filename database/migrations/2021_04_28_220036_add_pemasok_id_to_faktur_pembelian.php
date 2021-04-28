<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPemasokIdToFakturPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faktur_pembelian', function (Blueprint $table) {
            $table->unsignedInteger('pemasok_id')->after('kode')->index()->nullable();
            $table->foreign('pemasok_id')->references('id')->on('pemasok');
            $table->dropColumn('pemasok');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faktur_pembelian', function (Blueprint $table) {
            $table->string('pemasok');

            $table->dropForeign(['pemasok_id']);
            $table->dropColumn('pemasok_id');
        });
    }
}
