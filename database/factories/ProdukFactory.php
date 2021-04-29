<?php

namespace Database\Factories;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    public function definition(): array
    {
        return [
            "kode" => Str::random(10),
            "nama" => $this->faker->unique()->medicine,
            "harga_satuan" => rand(1, 100) * 5_000,
            "deskripsi" => $this->faker->realText(),
        ];
    }
}
