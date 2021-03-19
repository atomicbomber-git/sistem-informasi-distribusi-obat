<?php

namespace Database\Factories;

use App\Enums\TipeMutasiStock;
use App\Models\ItemFakturPembelian;
use App\Models\ItemFakturPenjualan;
use App\Models\Produk;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFakturPenjualanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ItemFakturPenjualan::class;

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
            "produk_kode" => $produk->kode,
            "jumlah" => rand(10, 100),
            "harga_satuan" => $produk->harga_satuan,
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

            $stock->mutasiStocks()->create([
                "item_faktur_pembelian_id" => $itemFakturPembelian->id,
                "jumlah" => $itemFakturPembelian->jumlah,
                "tipe" => TipeMutasiStock::PEMBELIAN,
                "transacted_at" => $itemFakturPembelian->faktur_pembelian->waktu_penerimaan,
            ]);
        });
    }
}
