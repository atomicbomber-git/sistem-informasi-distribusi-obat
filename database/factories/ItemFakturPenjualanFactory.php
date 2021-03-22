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
            "diskon" => rand(0, 7) / 100,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ItemFakturPenjualan $itemFakturPenjualan) {
            $itemFakturPenjualan->applyStockTransaction();
        });
    }
}
