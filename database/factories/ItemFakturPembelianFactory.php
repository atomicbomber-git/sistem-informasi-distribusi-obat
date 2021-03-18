<?php

namespace Database\Factories;

use App\Enums\TipeTransaksiStock;
use App\Models\ItemFakturPembelian;
use App\Models\Produk;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFakturPembelianFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ItemFakturPembelian::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $produk = 
            Produk::query()->inRandomOrder()->first() ?? 
            Produk::factory()->create();
        
        return [
            "kode_batch" => $this->faker->uuid,
            "produk_kode" => $produk->kode,
            "jumlah" => rand(5, 40) * 5,
            "harga_satuan" => rand(7, 9) / 10 * $produk->harga_satuan,
            "expired_at" => now()->addDays(rand(7, 365)),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ItemFakturPembelian $itemFakturPembelian) {
            $stock = new Stock([
                "kode_batch" => $itemFakturPembelian->kode_batch,
                "produk_kode" => $itemFakturPembelian->produk_kode,
                "jumlah" => $itemFakturPembelian->jumlah,
                "nilai_satuan" => $itemFakturPembelian->harga_satuan,
                "expired_at" => $itemFakturPembelian->expired_at,
            ]);

            $stock->save();

            $stock->transaksi_stocks()->create([
                "item_faktur_pembelian_id" => $itemFakturPembelian->id,
                "jumlah" => $itemFakturPembelian->jumlah,
                "tipe" => TipeTransaksiStock::PEMBELIAN,
                "transacted_at" => $itemFakturPembelian->faktur_pembelian->waktu_penerimaan,
            ]);
        });
    }
}
