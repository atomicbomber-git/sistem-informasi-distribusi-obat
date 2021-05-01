<?php

namespace Database\Seeders;

use App\Models\FakturPembelian;
use Database\Factories\FakturPembelianFactory;
use Database\Factories\ItemFakturPembelianFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
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

        Collection::times(100, function () {
            FakturPembelianFactory::new()
                ->state([
                    "waktu_penerimaan" => now()->subYear()->subDays(rand(0, 100))
                ])
                ->has(
                    ItemFakturPembelianFactory::new()->count(rand(10, 20)),
                    "item_faktur_pembelians"
                )
                ->create();
        });

        DB::commit();
    }
}
