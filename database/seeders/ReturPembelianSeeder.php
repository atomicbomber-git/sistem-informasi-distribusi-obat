<?php

namespace Database\Seeders;

use App\Models\FakturPembelian;
use Database\Factories\ReturPembelianFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReturPembelianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fakturPembelians = FakturPembelian::query()
            ->limit(20)
            ->inRandomOrder()
            ->get();
        
        DB::beginTransaction();

        foreach ($fakturPembelians as $fakturPembelian) {
            ReturPembelianFactory::new([
                "waktu_pengembalian" => $fakturPembelian->waktu_penerimaan->addDays(rand(1, 3)),
                "faktur_pembelian_kode" => $fakturPembelian->kode
            ])->create();
        }

        DB::commit();
    }
}
