<?php

namespace Webkul\Partner\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TitleSeeder extends Seeder
{
    /**
     * Seed the application's database with currencies.
     */
    public function run(): void
    {
        DB::table('partners_titles')->delete();

        DB::table('partners_titles')->insert([
            [
                'name' => 'Doctor',
                'short_name' => 'Dr.',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Madam',
                'short_name' => 'Mrs',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Miss',
                'short_name' => 'Miss',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Mister',
                'short_name' => 'Mr.',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Professor',
                'short_name' => 'Prof.',
                'creator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}