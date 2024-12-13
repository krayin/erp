<?php

namespace Webkul\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Employee\Models\CalendarAttendance;
use Webkul\Security\Models\User;

class CalendarAttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CalendarAttendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sequence'          => $this->faker->randomNumber(),
            'name'              => $this->faker->word,
            'day_of_week'       => $this->faker->randomElement(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
            'day_period'        => $this->faker->randomElement(['morning', 'afternoon', 'evening']),
            'week_type'         => $this->faker->randomElement(['odd', 'even', 'both']),
            'display_type'      => $this->faker->randomElement(['daily', 'weekly', 'monthly']),
            'date_from'         => $this->faker->date(),
            'date_to'           => $this->faker->date(),
            'hour_from'         => $this->faker->time(),
            'hour_to'           => $this->faker->time(),
            'durations_days'    => $this->faker->randomNumber(),
            'calendar_id'       => $this->faker->randomNumber(),
            'user_id'           => User::factory(),
        ];
    }
}
