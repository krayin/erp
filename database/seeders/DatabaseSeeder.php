<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Employee\Database\Seeders\EmployeeJobPosition;
use Webkul\Employee\Database\Seeders\EmploymentTypeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmploymentTypeSeeder::class,
            EmployeeJobPosition::class,
        ]);
    }
}
