<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $name = $this->faker->name;

        $username = $this->faker->unique()->passthrough(
            Str::snake($name)
        );

        return [
            'username' => $username,
            'name' => $name,
            'level' => $this->faker->randomElement(User::LEVELS),
            'password' => Hash::make($username),
        ];
    }
}
