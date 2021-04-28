<?php

namespace Database\Seeders;

use App\Models\Pemasok;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemasokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        Pemasok::factory()
            ->count(20)
            ->create();

        DB::commit();
    }
}
