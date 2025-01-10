<?php

namespace Webkul\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('recruitments_degrees')->delete();

        $degrees = [
            [
                'sort'       => 1,
                'name'       => 'Graduate',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 2,
                'name'       => 'Master',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 3,
                'name'       => 'Bachelor',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 4,
                'name'       => 'Doctoral Degree',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('recruitments_degrees')->insert($degrees);
    }
}
