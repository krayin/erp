<?php

namespace Webkul\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Employee\Models\Employee;
use Webkul\Employee\Models\EmployeeCategory;
use Webkul\Employee\Models\EmployeeEmployeeCategory;

class EmployeeEmployeeCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeEmployeeCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'category_id' => EmployeeCategory::factory(),
        ];
    }
}
