<?php

namespace Webkul\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Employee\Models\EmployeeJobPosition;
use Webkul\Recruitment\Models\Stage;

class StageSeeder extends Seeder
{
    /**
     * Seed the application's database with currencies.
     */
    public function run(): void
    {
        DB::table('recruitments_stages')->delete();

        $recruitmentStages = [
            [
                'sort'           => 1,
                'creator_id'     => 1,
                'name'           => 'New',
                'legend_blocked' => 'Blocked',
                'legend_done'    => 'Ready for Next Stage',
                'legend_normal'  => 'In Progress	',
                'hired_stage'    => false,
                'fold'           => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ], [

                'sort'           => 1,
                'creator_id'     => 1,
                'name'           => 'First Interview',
                'legend_blocked' => 'Blocked',
                'legend_done'    => 'Ready for Next Stage',
                'legend_normal'  => 'In Progress	',
                'hired_stage'    => false,
                'fold'           => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ], [

                'sort'           => 1,
                'creator_id'     => 1,
                'name'           => 'Initial Qualification',
                'legend_blocked' => 'Blocked',
                'legend_done'    => 'Ready for Next Stage',
                'legend_normal'  => 'In Progress	',
                'hired_stage'    => false,
                'fold'           => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ], [

                'sort'           => 1,
                'creator_id'     => 1,
                'name'           => 'Second Interview',
                'legend_blocked' => 'Blocked',
                'legend_done'    => 'Ready for Next Stage',
                'legend_normal'  => 'In Progress	',
                'hired_stage'    => false,
                'fold'           => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ], [

                'sort'           => 1,
                'creator_id'     => 1,
                'name'           => 'Contract Proposal',
                'legend_blocked' => 'Blocked',
                'legend_done'    => 'Ready for Next Stage',
                'legend_normal'  => 'In Progress	',
                'hired_stage'    => false,
                'fold'           => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ], [

                'sort'           => 1,
                'creator_id'     => 1,
                'name'           => 'Contract Signed',
                'legend_blocked' => 'Blocked',
                'legend_done'    => 'Ready for Next Stage',
                'legend_normal'  => 'In Progress	',
                'hired_stage'    => false,
                'fold'           => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]
        ];

        DB::table('recruitments_stages')->insert($recruitmentStages);

        $jobs = EmployeeJobPosition::all();
        $stage = Stage::first();

        $recruitmentStagesJobs = [];

        foreach ($jobs as $job) {
            $recruitmentStagesJobs[] = [
                'stage_id' => $stage->id,
                'job_id'   => $job->id,
            ];
        }

        DB::table('recruitments_stages_jobs')->insert($recruitmentStagesJobs);
    }
}
