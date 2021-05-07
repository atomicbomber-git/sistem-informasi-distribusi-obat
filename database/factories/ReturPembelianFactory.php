<?php

namespace Database\Factories;

use App\Models\FakturPembelian;
use App\Models\ReturPembelian;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReturPembelianFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReturPembelian::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "faktur_pembelian_kode" => FakturPembelian::query()
                    ->whereDoesntHave("returPembelian")
                    ->inRandomOrder()
                    ->value("kode") ?? FakturPembelian::factory()->create()->id,
            "waktu_pengembalian" => now()->subDays(rand(-100, 100)),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (ReturPembelian $returPembelian) {
            return $returPembelian->forceFill([
                "nomor" => ReturPembelian::getNextNomor($returPembelian->waktu_pengembalian)
            ]);
        });
    }
}
