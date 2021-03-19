<?php

namespace Database\Factories;

use App\Models\Produk;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class StockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Stock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "kode_batch" => (string) \Illuminate\Support\Str::uuid(),
            "produk_kode" => Produk::query()->inRandomOrder()->value("kode") ?? Produk::factory()->create()->kode,
            "jumlah" => rand(10, 100),
            "nilai_satuan" => rand(10, 100) * 1000,
            "expired_at" => now()->addWeeks(rand(10, 200)),
        ];
    }
}
