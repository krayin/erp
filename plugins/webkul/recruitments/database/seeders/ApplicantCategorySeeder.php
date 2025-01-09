<?php

namespace Webkul\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicantCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('recruitments_applicant_categories')->delete();

        $degrees = [
            [
                'name'       => 'Sales',
                'color'      => '#FF0000',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Manager',
                'color'      => '#00FF00',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'IT',
                'color'      => '#0000FF',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Reserve',
                'color'      => '#FFFF00',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('recruitments_applicant_categories')->insert($degrees);
    }
}
