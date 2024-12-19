<?php

namespace Webkul\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employeesCategories = [
            ['name' => 'Sales', 'color' => fake()->hexColor(), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Trainer', 'color' => fake()->hexColor(), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Employee', 'color' => fake()->hexColor(), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Consultant', 'color' => fake()->hexColor(), 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('employees_categories')->insert($employeesCategories);
    }
}
