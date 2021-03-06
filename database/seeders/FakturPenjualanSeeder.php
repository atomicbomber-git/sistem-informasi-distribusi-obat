<?php

namespace Database\Seeders;

use App\Models\FakturPembelian;
use App\Models\FakturPenjualan;
use App\Models\ItemFakturPembelian;
use App\Models\ItemFakturPenjualan;
use App\Models\Produk;
use Database\Factories\FakturPenjualanFactory;
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

        $this->seedStandardFakturPenjualans();
        $this->seedExperimentalFaktur();

        DB::commit();
    }

    private function seedStandardFakturPenjualans(): void
    {
        Collection::times(10, function (int $number) {
            $produks = Produk::query()
                ->hasQuantityInHand()
                ->withQuantityInHand()
                ->inRandomOrder()
                ->limit(rand(2, 10))
                ->get();

            FakturPenjualan::factory()
                ->state(["waktu_pengeluaran" => now()->subDays(60 - $number)])
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
    }

    private function seedExperimentalFaktur(): void
    {
        $produk = Produk::factory()->create([
            "nama" => "EXPERIMENTAL"
        ]);

        $fakturPembelian = FakturPembelian::factory()
            ->has(
                ItemFakturPembelian::factory([
                    "produk_kode" => $produk->getKey(),
                    "jumlah" => 200,
                ]),
                "item_faktur_pembelians"
            )
            ->create([
                "waktu_penerimaan" => now()->subDays(2),
            ]);

        $fakturPenjualan = FakturPenjualanFactory::new([
            "waktu_pengeluaran" => now()->subDays(1)
        ])->has(
            ItemFakturPenjualan::factory([
                "produk_kode" => $produk->getKey(),
                "jumlah" => 100,
            ]),
            "itemFakturPenjualans",
        )->create();
    }
}
