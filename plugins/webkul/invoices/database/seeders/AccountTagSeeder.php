<?php

namespace Webkul\Invoice\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('invoices_account_tags')->delete();

        $accountTags = [
            [
                'color' => '#FF0000',
                'country_id' => 1,
                'creator_id' => 1,
                'applicability' => 'accounts',
                'name' => 'Operating Activities',
                'is_active' => true,
                'tax_negate' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color' => '#00FF00',
                'country_id' => 1,
                'creator_id' => 1,
                'applicability' => 'accounts',
                'name' => 'Financing Activities',
                'is_active' => true,
                'tax_negate' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color' => '#0000FF',
                'country_id' => 1,
                'creator_id' => 1,
                'applicability' => 'accounts',
                'name' => 'Investing & Extraordinary Activities',
                'is_active' => true,
                'tax_negate' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color' => '#FFFF00',
                'country_id' => 1,
                'creator_id' => 1,
                'applicability' => 'accounts',
                'name' => 'Demo Capital Account',
                'is_active' => true,
                'tax_negate' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color' => '#FF00FF',
                'country_id' => 1,
                'creator_id' => 1,
                'applicability' => 'accounts',
                'name' => 'Demo Stock Account',
                'is_active' => true,
                'tax_negate' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color' => '#00FFFF',
                'country_id' => 1,
                'creator_id' => 1,
                'applicability' => 'accounts',
                'name' => 'Demo Sale of Land Account',
                'is_active' => true,
                'tax_negate' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color' => '#000000',
                'country_id' => 1,
                'creator_id' => 1,
                'applicability' => 'accounts',
                'name' => 'Demo CEO Wages Account',
                'is_active' => true,
                'tax_negate' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'color' => '#FFFFFF',
                'country_id' => 1,
                'creator_id' => 1,
                'applicability' => 'accounts',
                'name' => 'Office Furniture',
                'is_active' => true,
                'tax_negate' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('invoices_account_tags')->insert($accountTags);
    }
}
