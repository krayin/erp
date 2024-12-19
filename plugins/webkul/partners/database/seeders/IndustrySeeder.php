<?php

namespace Webkul\Partner\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustrySeeder extends Seeder
{
    /**
     * Seed the application's database with currencies.
     */
    public function run(): void
    {
        DB::table('partners_industries')->delete();

        DB::table('partners_industries')->insert([
            [
                'name' => 'Administrative/Utilities',
                'description' => 'Administrative and Support Service Activities',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Agriculture',
                'description' => 'Agriculture, Forestry, and Fishing',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Construction',
                'description' => 'Construction',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Education',
                'description' => 'Education',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Energy Supply',
                'description' => 'Electricity, Gas, Steam, and Air Conditioning Supply',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Entertainment',
                'description' => 'Arts, Entertainment, and Recreation',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Extraterritorial',
                'description' => 'Activities of Extraterritorial Organisations and Bodies',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Finance/Insurance',
                'description' => 'Financial and Insurance Activities',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Food/Hospitality',
                'description' => 'Accommodation and Food Service Activities',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Health/Social',
                'description' => 'Human Health and Social Work Activities',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Households',
                'description' => 'Activities of Households as Employers and Undifferentiated Goods- and Services-Producing Activities',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'IT/Communication',
                'description' => 'Information and Communication',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Manufacturing',
                'description' => 'Manufacturing',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Mining',
                'description' => 'Mining and Quarrying',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Other Services',
                'description' => 'Other Service Activities',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Public Administration',
                'description' => 'Public Administration and Defence; Compulsory Social Security',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Real Estate',
                'description' => 'Real Estate Activities',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Scientific',
                'description' => 'Professional, Scientific, and Technical Activities',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Transportation/Logistics',
                'description' => 'Transportation and Storage',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Water Supply',
                'description' => 'Water Supply; Sewerage, Waste Management, and Remediation Activities',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Wholesale/Retail',
                'description' => 'Wholesale and Retail Trade; Repair of Motor Vehicles and Motorcycles',
                'is_active' => true,
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}