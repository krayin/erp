<?php

namespace Webkul\Core\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Core\Models\User;

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
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => $this->faker->name,
            'email'             => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password'          => bcrypt('admin'),
            'remember_token'    => \Illuminate\Support\Str::random(10),
        ];
    }
}
