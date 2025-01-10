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
        DB::table('employees_categories')->delete();

        $employeesCategories = [
            ['creator_id' => 1, 'name' => 'Sales', 'color' => fake()->hexColor(), 'created_at' => now(), 'updated_at' => now()],
            ['creator_id' => 1, 'name' => 'Trainer', 'color' => fake()->hexColor(), 'created_at' => now(), 'updated_at' => now()],
            ['creator_id' => 1, 'name' => 'Employee', 'color' => fake()->hexColor(), 'created_at' => now(), 'updated_at' => now()],
            ['creator_id' => 1, 'name' => 'Consultant', 'color' => fake()->hexColor(), 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('employees_categories')->insert($employeesCategories);
    }
}
