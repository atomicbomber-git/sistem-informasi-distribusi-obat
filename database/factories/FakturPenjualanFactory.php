<?php

namespace Database\Factories;

use App\Models\FakturPembelian;
use App\Models\FakturPenjualan;
use Illuminate\Database\Eloquent\Factories\Factory;

class FakturPenjualanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FakturPenjualan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            "kode" => $this->faker->unique()->uuid,
            "pelanggan" => $this->faker->firstName,
            "waktu_pengeluaran" => now()->subMinutes(rand(0, 1_000_000)),
        ];
    }
}
