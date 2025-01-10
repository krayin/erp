<?php

namespace Webkul\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityPlanTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('activity_plan_templates')->delete();

        $activityPlans = [
            [
                'sort'             => 1,
                'plan_id'          => 1,
                'activity_type_id' => 3,
                'delay_count'      => 0,
                'delay_unit'       => 'days',
                'delay_from'       => 'before_plan_date',
                'summary'          => 'Organize knowledge transfer inside the team',
                'responsible_type' => 'manager',
                'note'             => '<p>Organize knowledge transfer inside the team</p>',
                'creator_id'       => 1,
            ],
            [
                'sort'             => 2,
                'plan_id'          => 1,
                'activity_type_id' => 3,
                'delay_count'      => 0,
                'delay_unit'       => 'days',
                'delay_from'       => 'before_plan_date',
                'summary'          => 'Take Back HR Materials',
                'responsible_type' => 'manager',
                'note'             => '<p>Take Back HR Materials</p>',
                'creator_id'       => 1,
            ],
            [
                'sort'             => 3,
                'plan_id'          => 2,
                'activity_type_id' => 3,
                'delay_count'      => 0,
                'delay_unit'       => 'days',
                'delay_from'       => 'before_plan_date',
                'summary'          => 'Setup IT Materials',
                'responsible_type' => 'manager',
                'note'             => '<p>Setup IT Materials</p>',
                'creator_id'       => 1,
            ],
            [
                'sort'             => 4,
                'plan_id'          => 2,
                'activity_type_id' => 3,
                'delay_count'      => 0,
                'delay_unit'       => 'days',
                'delay_from'       => 'before_plan_date',
                'summary'          => 'Plan Training',
                'responsible_type' => 'manager',
                'note'             => '<p>Plan Training</p>',
                'creator_id'       => 1,
            ],
            [
                'sort'             => 5,
                'plan_id'          => 2,
                'activity_type_id' => 3,
                'delay_count'      => 0,
                'delay_unit'       => 'days',
                'delay_from'       => 'before_plan_date',
                'summary'          => 'Training',
                'responsible_type' => 'manager',
                'note'             => '<p>Training</p>',
                'creator_id'       => 1,
            ],
        ];

        DB::table('activity_plan_templates')->insert($activityPlans);
    }
}
