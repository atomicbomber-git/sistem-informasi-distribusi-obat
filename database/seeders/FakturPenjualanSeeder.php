<?php

namespace Database\Seeders;

use App\Models\FakturPenjualan;
use App\Models\ItemFakturPenjualan;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FakturPenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        Collection::times(50, function () {
            $produks = Produk::query()
                ->hasQuantityInHand()
                ->withQuantityInHand()
                ->inRandomOrder()
                ->limit(rand(2, 10))
                ->get();

            FakturPenjualan::factory()
                ->state(["waktu_pengeluaran" => now()->subDays(rand(0, 60))])
                ->has(
                    ItemFakturPenjualan::factory()
                        ->state(new Sequence(
                            ...$produks->map(function (Produk $produk) {
                                return [
                                    "produk_kode" => $produk->kode,
                                    "jumlah" => round(bcmul($produk->quantity_in_hand, rand(5, 10) / 10)),
                                    "harga_satuan" => bcmul($produk->harga_satuan, rand(15, 20) / 10),
                                    "diskon" => rand(0, 14),
                                ];
                            })->toArray()
                        ))
                        ->count($produks->count()),
                    "itemFakturPenjualans",
                )
                ->create();
        });

        DB::commit();
    }
}
