<?php

namespace Database\Factories;

use App\Models\FakturPenjualan;
use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Psy\Util\Str;

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
            "nomor" => FakturPenjualan::getNextNomor(),
            "pelanggan_id" => Pelanggan::query()->inRandomOrder()->value("id") ?? Pelanggan::factory()->create()->id,
            "diskon" => rand(0, 40),
            "pajak" => 10,
            "waktu_pengeluaran" => now()->subMinutes(rand(0, 1_000_000)),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (FakturPenjualan $fakturPenjualan) {
            $fakturPenjualan->nomor = FakturPenjualan::getNextNomor($fakturPenjualan->waktu_pengeluaran);
        });
    }
}
