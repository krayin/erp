<?php

namespace Webkul\Project\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('projects_project_stages')->delete();

        DB::table('projects_project_stages')->insert([
            [
                'name'       => 'To Do',
                'is_active'  => 1,
                'sort'       => 1,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name'       => 'In Progress',
                'is_active'  => 1,
                'sort'       => 2,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name'       => 'Done',
                'is_active'  => 1,
                'sort'       => 3,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name'       => 'Cancelled',
                'is_active'  => 1,
                'sort'       => 4,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
