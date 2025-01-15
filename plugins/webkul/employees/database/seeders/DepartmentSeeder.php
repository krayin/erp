<?php

namespace Webkul\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Employee\Models\Employee;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees_departments')->delete();

        $departments = [
            [
                'company_id'           => 1,
                'creator_id'           => 1,
                'name'                 => 'Administration',
                'complete_name'        => 'Administration',
                'color'                => '#4e0554',
                'deleted_at'           => null,
            ],
            [
                'company_id'          => 1,
                'creator_id'          => 1,
                'name'                => 'Long Term Projects',
                'complete_name'       => 'Long Term Projects',
                'color'               => '#5d0a6e',
                'deleted_at'          => null,
            ],
            [
                'company_id'          => 1,
                'creator_id'          => 1,
                'name'                => 'Management',
                'complete_name'       => 'Management',
                'color'               => '#4e095c',
                'deleted_at'          => null,
            ],
            [
                'company_id'          => 1,
                'creator_id'          => 1,
                'name'                => 'Professional Services',
                'complete_name'       => 'Professional Services',
                'color'               => '#5e0870',
                'deleted_at'          => null,
            ],
            [
                'company_id'          => 1,
                'creator_id'          => 1,
                'name'                => 'R&D USA',
                'complete_name'       => 'R&D USA',
                'color'               => '#420957',
                'deleted_at'          => null,
            ],
            [
                'company_id'          => 1,
                'creator_id'          => 1,
                'name'                => 'Research & Development',
                'complete_name'       => 'Research & Development',
                'color'               => '#570919',
                'deleted_at'          => null,
            ],
            [
                'company_id'          => 1,
                'creator_id'          => 1,
                'name'                => 'Sales',
                'complete_name'       => 'Sales',
                'color'               => '#590819',
                'deleted_at'          => null,
            ],
        ];

        DB::table('employees_departments')->insert(collect($departments)->map(function ($department) {
            $department['created_at'] = now();
            $department['updated_at'] = now();
            $department['manager_id'] = Employee::inRandomOrder()->first()->id;

            return $department;
        })->toArray());
    }
}
