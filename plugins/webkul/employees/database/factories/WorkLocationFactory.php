<?php

namespace Webkul\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Employee\Models\WorkLocation;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class WorkLocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkLocation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id'      => Company::factory(),
            'user_id'         => User::factory(),
            'name'            => $this->faker->name,
            'location_type'   => $this->faker->word,
            'location_number' => $this->faker->numberBetween(1, 100),
            'active'          => 1,
        ];
    }
}
