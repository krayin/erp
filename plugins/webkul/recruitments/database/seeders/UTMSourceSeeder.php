<?php

namespace Webkul\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UTMSourceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('recruitments_utm_sources')->delete();

        $sources = [
            'Search engine',
            'Lead Recall',
            'Newsletter',
            'Facebook',
            'X',
            'LinkedIn',
            'Monster',
            'Glassdoor',
            'Craigslist',
        ];

        DB::table('recruitments_utm_sources')->insert(collect($sources)->map(function ($medium) {
            return [
                'name'       => $medium,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray());
    }
}
