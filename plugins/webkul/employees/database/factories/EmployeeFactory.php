<?php

namespace Webkul\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Employee\Models\Department;
use Webkul\Employee\Models\DepartureReason;
use Webkul\Employee\Models\Employee;
use Webkul\Employee\Models\EmployeeJobPosition;
use Webkul\Employee\Models\WorkLocation;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Country;
use Webkul\Support\Models\State;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id'                     => Company::factory(),
            'user_id'                        => User::factory(),
            'creator_id'                     => User::factory(),
            'calendar_id'                    => null,
            'department_id'                  => Department::factory(),
            'attendance_manager_id'          => User::factory(),
            'job_id'                         => EmployeeJobPosition::factory(),
            'partner_id'                     => null,
            'work_location_id'               => WorkLocation::factory(),
            'parent_id'                      => User::factory(),
            'coach_id'                       => User::factory(),
            'country_id'                     => Country::factory(),
            'private_state_id'               => State::factory(),
            'private_country_id'             => Country::factory(),
            'country_of_birth'               => Country::factory(),
            'bank_account_id'                => null,
            'departure_reason_id'            => DepartureReason::factory(),
            'name'                           => $this->faker->name,
            'job_title'                      => $this->faker->jobTitle,
            'work_phone'                     => $this->faker->phoneNumber,
            'mobile_phone'                   => $this->faker->phoneNumber,
            'color'                          => $this->faker->safeColorName,
            'work_email'                     => $this->faker->unique()->safeEmail,
            'children'                       => $this->faker->numberBetween(0, 5),
            'distance_home_work'             => $this->faker->numberBetween(5, 100),
            'km_home_work'                   => $this->faker->numberBetween(5, 100),
            'distance_home_work_unit'        => $this->faker->randomElement(['km', 'miles']),
            'private_street1'                => $this->faker->streetAddress,
            'private_street2'                => $this->faker->secondaryAddress,
            'private_city'                   => $this->faker->city,
            'private_zip'                    => $this->faker->postcode,
            'private_phone'                  => $this->faker->phoneNumber,
            'private_email'                  => $this->faker->unique()->safeEmail,
            'lang'                           => $this->faker->languageCode,
            'gender'                         => $this->faker->randomElement(),
            'birthday'                       => $this->faker->date(),
            'marital'                        => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed']),
            'spouse_complete_name'           => $this->faker->name,
            'spouse_birthdate'               => $this->faker->date(),
            'place_of_birth'                 => $this->faker->city,
            'ssnid'                          => $this->faker->uuid,
            'sinid'                          => $this->faker->uuid,
            'identification_id'              => $this->faker->uuid,
            'passport_id'                    => $this->faker->uuid,
            'permit_no'                      => $this->faker->uuid,
            'visa_no'                        => $this->faker->uuid,
            'certificate'                    => $this->faker->word,
            'study_field'                    => $this->faker->word,
            'study_school'                   => $this->faker->company,
            'emergency_contact'              => $this->faker->name,
            'emergency_phone'                => $this->faker->phoneNumber,
            'employee_type'                  => $this->faker->randomElement(['full-time', 'part-time', 'contractor']),
            'barcode'                        => $this->faker->ean13,
            'pin'                            => $this->faker->randomNumber(6, true),
            'private_car_plate'              => $this->faker->bothify('??-###-##'),
            'visa_expire'                    => $this->faker->date(),
            'work_permit_expiration_date'    => $this->faker->date(),
            'departure_date'                 => $this->faker->optional()->date(),
            'departure_description'          => $this->faker->optional()->text,
            'employee_properties'            => $this->faker->optional()->json,
            'additional_note'                => $this->faker->optional()->text,
            'notes'                          => $this->faker->optional()->text,
            'is_active'                      => $this->faker->boolean(),
            'is_flexible'                    => $this->faker->boolean(),
            'is_fully_flexible'              => $this->faker->boolean(),
            'work_permit_scheduled_activity' => $this->faker->boolean(),
        ];
    }
}
