<?php

namespace Database\Seeders;

use App\Models\FakturPenjualan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakturPenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        FakturPenjualan::factory()
            ->count(100)
            ->create();

        DB::commit();
    }
}
