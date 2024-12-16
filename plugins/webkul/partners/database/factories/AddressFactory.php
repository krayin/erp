<?php

namespace Webkul\Partner\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Partner\Enums\AccountType;
use Webkul\Partner\Models\Address;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Country;
use Webkul\Support\Models\State;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Webkul\Partner\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type'       => AccountType::INDIVIDUAL,
            'name'       => fake()->name(),
            'email'      => fake()->unique()->safeEmail(),
            'phone'      => fake()->phoneNumber(),
            'street1'    => fake()->streetAddress(),
            'street2'    => fake()->streetAddress(),
            'city'       => fake()->city(),
            'zip'        => fake()->postcode(),
            'type'       => 'contact',
            'name'       => fake()->name(),
            'email'      => fake()->unique()->safeEmail(),
            'phone'      => fake()->phoneNumber(),
            'street1'    => fake()->streetAddress(),
            'street2'    => fake()->streetAddress(),
            'city'       => fake()->city(),
            'zip'        => fake()->postcode(),
            'state_id'   => State::factory(),
            'country_id' => Country::factory(),
            'creator_id' => User::factory(),
            'partner_id' => Partner::factory(),
        ];
    }
}
