<?php

namespace Webkul\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Support\Models\Company;

class WorkLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees_work_locations')->delete();

        $company = Company::find(1) ?? Company::factory()->create();

        $workLocations = [
            [
                'name'               => 'Home',
                'company_id'         => $company->id,
                'location_type'      => 'home',
                'is_active'          => 1,
                'creator_id'         => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'name'               => 'Building 1, Second Floor',
                'company_id'         => $company->id,
                'location_type'      => 'office',
                'is_active'          => 1,
                'creator_id'         => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'name'               => 'Other',
                'company_id'         => $company->id,
                'location_type'      => 'other',
                'is_active'          => 1,
                'creator_id'         => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ];

        DB::table('employees_work_locations')->insert($workLocations);
    }
}
