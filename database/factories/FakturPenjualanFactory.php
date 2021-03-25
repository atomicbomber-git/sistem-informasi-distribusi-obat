<?php

namespace Database\Factories;

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
            "nomor" => FakturPenjualan::getNextId(),
            "pelanggan" => $this->faker->firstName,
            "diskon" => rand(0, 40),
            "pajak" => 10 / 100,
            "waktu_pengeluaran" => now()->subMinutes(rand(0, 1_000_000)),
        ];
    }
}
