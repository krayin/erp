<?php

namespace Webkul\Support\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;

class UOMCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        DB::table('unit_of_measure_categories')->delete();

        DB::table('unit_of_measure_categories')->insert([
            [
                'name'       => 'Unit',
                'creator_id' => $user?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name'       => 'Weight',
                'creator_id' => $user?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name'       => 'Working Time',
                'creator_id' => $user?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name'       => 'Length / Distance',
                'creator_id' => $user?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name'       => 'Surface',
                'creator_id' => $user?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name'       => 'Volume',
                'creator_id' => $user?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
