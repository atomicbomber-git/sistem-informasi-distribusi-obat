<?php

namespace Database\Seeders;

use App\Models\FakturPembelian;
use Database\Factories\FakturPembelianFactory;
use Database\Factories\ItemFakturPembelianFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakturPembelianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        FakturPembelianFactory::new(["waktu_penerimaan" => now()->subYear()->subDays(rand(0, 100))])
            ->count(100)
            ->has(
                ItemFakturPembelianFactory::new()->count(rand(1, 5)),
                "item_faktur_pembelians"
            )
            ->create();

        DB::commit();
    }
}
