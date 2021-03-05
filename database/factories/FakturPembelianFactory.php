<?php

namespace Database\Factories;

use App\Models\FakturPembelian;
use Illuminate\Database\Eloquent\Factories\Factory;

class FakturPembelianFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FakturPembelian::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            "kode" => $this->faker->unique()->uuid,
            "pemasok" => $this->faker->company,
            "waktu_pembelian" => now()->subMinutes(rand(0, 1_000_000)),
        ];
    }
}
