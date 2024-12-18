<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Employee\Database\Seeders\CalendarAttendanceSeeder;
use Webkul\Employee\Database\Seeders\CalendarSeeder;
use Webkul\Employee\Database\Seeders\DepartureReasonSeeder;
use Webkul\Employee\Database\Seeders\EmployeeCategorySeeder;
use Webkul\Employee\Database\Seeders\EmployeeJobPositionSeeder;
use Webkul\Employee\Database\Seeders\EmploymentTypeSeeder;
use Webkul\Employee\Database\Seeders\SkillTypeSeeder;
use Webkul\Employee\Database\Seeders\WorkLocationSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmploymentTypeSeeder::class,
            EmployeeJobPositionSeeder::class,
            SkillTypeSeeder::class,
            WorkLocationSeeder::class,
            EmployeeCategorySeeder::class,
            DepartureReasonSeeder::class,
            CalendarSeeder::class,
            CalendarAttendanceSeeder::class,
        ]);
    }
}
