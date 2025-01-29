<?php

namespace Webkul\Invoice\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Invoice\Models\PaymentDueTerm;
use Webkul\Invoice\Models\PaymentTerm;
use Webkul\Security\Models\User;
use Webkul\Invoice\Enums\DueTermValue;
use Webkul\Invoice\Enums\DelayType;

class PaymentDueTermFactory extends Factory
{
    protected $model = PaymentDueTerm::class;

    public function definition(): array
    {
        return [
            'payment_id'      => PaymentTerm::factory(),
            'creator_id'      => User::factory(),
            'value'           => $this->faker->randomElement([DueTermValue::PERCENT->value, DueTermValue::FIXED->value]),
            'value_amount'    => $this->faker->randomFloat(2, 0, 100),
            'delay_type'      => DelayType::DAYS_AFTER->value,
            'days_next_month' => $this->faker->numberBetween(0, 31),
            'nb_days'         => $this->faker->numberBetween(0, 60),
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }
}
