<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->create([
                "name" => "Admin",
                "username" => "admin",
                "password" => \Hash::make("admin"),
            ]);
    }
}
