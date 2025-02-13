<?php

namespace Webkul\Sale\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sales_teams')->delete();

        $degrees = [
            [
                'sort'            => 0,
                'company_id'      => 1,
                'user_id'         => 1,
                'creator_id'      => 1,
                'color'           => '#FF0000',
                'name'            => 'Sales',
                'is_active'       => true,
                'invoiced_target' => 25000,
            ],
            [
                'sort'            => 1,
                'company_id'      => 1,
                'user_id'         => 1,
                'creator_id'      => 1,
                'color'           => '#00FF00',
                'name'            => 'Website',
                'is_active'       => false,
                'invoiced_target' => 5000,
            ],
            [
                'sort'            => 2,
                'company_id'      => 1,
                'user_id'         => 1,
                'creator_id'      => 1,
                'color'           => '#0000FF',
                'name'            => 'Point of Sale',
                'is_active'       => true,
                'invoiced_target' => 55000,
            ],
            [
                'sort'            => 3,
                'company_id'      => 1,
                'user_id'         => 1,
                'creator_id'      => 1,
                'color'           => '#FFFF00',
                'name'            => 'Pre-Sales',
                'is_active'       => true,
                'invoiced_target' => 55000,
            ],
        ];

        DB::table('sales_teams')->insert($degrees);
    }
}
