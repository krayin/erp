<?php

namespace Webkul\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees_skill_types')->delete();

        $skillTypes = [
            [
                'name'               => 'Languages',
                'color'              => 'danger',
                'is_active'          => 1,
                'creator_id'         => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'name'               => 'Soft Skills',
                'color'              => 'success',
                'is_active'          => 1,
                'creator_id'         => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'name'               => 'Programming Languages',
                'color'              => 'warning',
                'is_active'          => 1,
                'creator_id'         => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'name'               => 'IT',
                'color'              => 'info',
                'is_active'          => 1,
                'creator_id'         => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'name'               => 'Marketing',
                'color'              => 'gray',
                'is_active'          => 1,
                'creator_id'         => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ];

        DB::table('employees_skill_types')->insert($skillTypes);

        $this->call(SkillSeeder::class);
    }
}
