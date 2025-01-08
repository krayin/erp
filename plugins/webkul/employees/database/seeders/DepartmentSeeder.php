<?php

namespace Webkul\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Employee\Models\Department;
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
                'parent_id'            => 3,
                'master_department_id' => 3,
                'creator_id'           => 1,
                'name'                 => 'Administration',
                'complete_name'        => 'Management / Administration',
                'parent_path'          => '/3/',
                'color'                => '#4e0554',
                'deleted_at'           => null,
            ],
            [
                'company_id'          => 1,
                'parent_id'           => 5,
                'master_department_id' => 3,
                'creator_id'          => 1,
                'name'                => 'Long Term Projects',
                'complete_name'       => 'Management / Research & Development / R&D USA / Long Term Projects',
                'parent_path'         => '/3/6/5/',
                'color'               => '#5d0a6e',
                'deleted_at'          => null,
            ],
            [
                'company_id'          => 1,
                'parent_id'           => null,
                'master_department_id' => null,
                'creator_id'          => 1,
                'name'                => 'Management',
                'complete_name'       => 'Management',
                'parent_path'         => '/',
                'color'               => '#4e095c',
                'deleted_at'          => null,
            ],
            [
                'manager_id'          => 4,
                'company_id'          => 1,
                'parent_id'           => 3,
                'master_department_id' => 3,
                'creator_id'          => 1,
                'name'                => 'Professional Services',
                'complete_name'       => 'Management / Professional Services',
                'parent_path'         => '/3/',
                'color'               => '#5e0870',
                'deleted_at'          => null,
            ],
            [
                'manager_id'          => 3,
                'company_id'          => 1,
                'parent_id'           => 6,
                'master_department_id' => 3,
                'creator_id'          => 1,
                'name'                => 'R&D USA',
                'complete_name'       => 'Management / Research & Development / R&D USA',
                'parent_path'         => '/3/6/',
                'color'               => '#420957',
                'deleted_at'          => null,
            ],
            [
                'manager_id'          => 4,
                'company_id'          => 1,
                'parent_id'           => 3,
                'master_department_id' => 3,
                'creator_id'          => 1,
                'name'                => 'Research & Development',
                'complete_name'       => 'Management / Research & Development',
                'parent_path'         => '/3/',
                'color'               => '#570919',
                'deleted_at'          => null,
            ],
            [
                'manager_id'          => 1,
                'company_id'          => 1,
                'parent_id'           => 3,
                'master_department_id' => 3,
                'creator_id'          => 1,
                'name'                => 'Sales',
                'complete_name'       => 'Management / Sales',
                'parent_path'         => '/3/',
                'color'               => '#590819',
                'deleted_at'          => null,
            ],
        ];

        foreach ($departments as $department) {
            Department::create(array_merge($department, [
                'manager_id' => Employee::inRandomOrder()->first()->id ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
