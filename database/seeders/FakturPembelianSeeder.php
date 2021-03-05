<?php

namespace Database\Seeders;

use App\Models\FakturPembelian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakturPembelianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        FakturPembelian::factory()
            ->count(3)
            ->create();

        DB::commit();
    }
}
