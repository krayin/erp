<?php

namespace Webkul\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefuseReasonSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('recruitments_refuse_reasons')->delete();

        $degrees = [
            [
                'sort'       => 1,
                'name'       => 'Does not fit the job requirements',
                'creator_id' => 1,
                'template'   => 'applicant-refuse',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 2,
                'name'       => 'Refused by applicant: job fit',
                'creator_id' => 1,
                'template'   => 'applicant-not-interested',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 3,
                'name'       => 'Job already fulfilled',
                'creator_id' => 1,
                'template'   => 'applicant-refuse',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 4,
                'name'       => 'Duplicate',
                'creator_id' => 1,
                'template'   => 'applicant-refuse',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 4,
                'name'       => 'Spam',
                'creator_id' => 1,
                'template'   => 'applicant-not-interested',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 4,
                'name'       => 'Refused by applicant: salary',
                'template'   => 'applicant-not-interested',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('recruitments_refuse_reasons')->insert($degrees);
    }
}
