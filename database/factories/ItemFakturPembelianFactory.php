<?php

namespace Database\Factories;

use App\Enums\StockStatus;
use App\Enums\TipeMutasiStock;
use App\Models\ItemFakturPembelian;
use App\Models\Produk;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            "kode_batch" => Str::random(10),
            "produk_kode" => $produk->kode,
            "jumlah" => rand(5, 40) * 5,
            "harga_satuan" => rand(7, 9) / 10 * $produk->harga_satuan,
            "expired_at" => now()->addDays(rand(7, 365)),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ItemFakturPembelian $itemFakturPembelian) {
            $itemFakturPembelian->applyStockTransaction();
        });
    }
}
