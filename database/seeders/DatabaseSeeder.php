<?php

namespace Database\Seeders;

use App\Models\FakturPenjualan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminSeeder::class);
        $this->call(ProdukSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(FakturPenjualanSeeder::class);
    }
}
