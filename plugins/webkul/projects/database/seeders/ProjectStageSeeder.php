<?php

namespace Webkul\Partner\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStageSeeder extends Seeder
{
    /**
     * Seed the application's database with currencies.
     */
    public function run(): void
    {
        DB::table('	projects_project_stages')->delete();

        DB::table('	projects_project_stages')->insert([
            [
                'name' => 'To Do',
                'is_active' => 1,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'In Progress',
                'is_active' => 1,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Done',
                'is_active' => 1,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Cancelled',
                'is_active' => 1,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}