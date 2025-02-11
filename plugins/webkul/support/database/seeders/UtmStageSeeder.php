<?php

namespace Webkul\Support\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtmStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('utm_stages')->delete();

        $now = now();

        $utmStages = [
            [
                'sort' => 1,
                'name' => 'New',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'sort' => 2,
                'name' => 'Schedule',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'sort' => 3,
                'name' => 'Design',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'sort' => 3,
                'name' => 'Sent',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::table('utm_stages')->insert($utmStages);
    }
}
