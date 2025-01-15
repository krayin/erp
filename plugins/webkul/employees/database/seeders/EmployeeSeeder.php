<?php

namespace Webkul\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Employee\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees_employees')->delete();

        $employees = [
            [
                'time_zone'      => 'UTC',
                'creator_id'     => 1,
                'name'           => 'Paul Williams',
                'job_title'      => 'Experienced Developer',
                'work_email'     => 'paul@example.com',
                'employee_type'  => 'employee',
                'is_active'      => 1,
            ],
            [
                'time_zone'      => 'America/New_York',
                'creator_id'     => 1,
                'name'           => 'John Doe',
                'job_title'      => 'Junior Developer',
                'work_email'     => 'john@example.com',
                'employee_type'  => 'employee',
                'is_active'      => 1,
            ],
            [
                'time_zone'      => 'Europe/London',
                'creator_id'     => 1,
                'name'           => 'Jane Smith',
                'job_title'      => 'Project Manager',
                'work_email'     => 'jane@example.com',
                'employee_type'  => 'employee',
                'is_active'      => 1,
            ],
            [
                'time_zone'      => 'Asia/Kolkata',
                'creator_id'     => 1,
                'name'           => 'Ravi Kumar',
                'job_title'      => 'Team Lead',
                'work_email'     => 'ravi@example.com',
                'employee_type'  => 'employee',
                'is_active'      => 1,
            ],
            [
                'time_zone'      => 'Australia/Sydney',
                'creator_id'     => 1,
                'name'           => 'Emily Davis',
                'job_title'      => 'QA Engineer',
                'work_email'     => 'emily@example.com',
                'employee_type'  => 'employee',
                'is_active'      => 1,
            ],
            [
                'time_zone'      => 'America/Los_Angeles',
                'creator_id'     => 1,
                'name'           => 'Michael Brown',
                'job_title'      => 'UX Designer',
                'work_email'     => 'michael@example.com',
                'employee_type'  => 'employee',
                'is_active'      => 1,
            ],
            [
                'time_zone'      => 'Asia/Tokyo',
                'creator_id'     => 1,
                'name'           => 'Hiro Tanaka',
                'job_title'      => 'Backend Developer',
                'work_email'     => 'hiro@example.com',
                'employee_type'  => 'employee',
                'is_active'      => 1,
            ],
            [
                'time_zone'      => 'Africa/Johannesburg',
                'creator_id'     => 1,
                'name'           => 'Linda Ndlovu',
                'job_title'      => 'HR Manager',
                'work_email'     => 'linda@example.com',
                'employee_type'  => 'employee',
                'is_active'      => 1,
            ],
            [
                'time_zone'      => 'Europe/Berlin',
                'creator_id'     => 1,
                'name'           => 'Hans Müller',
                'job_title'      => 'Frontend Developer',
                'work_email'     => 'hans@example.com',
                'employee_type'  => 'employee',
                'is_active'      => 1,
            ],
            [
                'time_zone'      => 'America/Chicago',
                'creator_id'     => 1,
                'name'           => 'Grace Wilson',
                'job_title'      => 'Data Scientist',
                'work_email'     => 'grace@example.com',
                'employee_type'  => 'employee',
                'is_active'      => 1,
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create(array_merge($employee, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
