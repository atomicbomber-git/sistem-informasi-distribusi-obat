<?php

namespace Database\Factories;

use App\Models\FakturPembelian;
use App\Models\Pemasok;
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
            "pemasok_id" => Pemasok::query()->inRandomOrder()->value("id") ?? Pemasok::factory()->create()->id,
            "waktu_penerimaan" => now()->subMinutes(rand(0, 1_000_000)),
        ];
    }
}
