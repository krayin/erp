<?php

namespace Webkul\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Employee\Models\EmployeeAddress;

class EmployeeAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'state_id'           => null,
            'country_id'         => null,
            'creator_id'         => null,
            'partner_address_id' => null,
            'name'               => $this->faker->name,
            'email'              => $this->faker->unique()->safeEmail,
            'phone'              => $this->faker->phoneNumber,
            'street1'            => $this->faker->streetAddress,
            'street2'            => $this->faker->secondaryAddress,
            'city'               => $this->faker->city,
            'zip'                => $this->faker->postcode,
        ];
    }
}
