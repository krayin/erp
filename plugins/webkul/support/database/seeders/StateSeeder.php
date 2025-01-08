<?php

namespace Webkul\Support\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('plugins/webkul/security/src/Data/states.json');

        if (File::exists($path)) {
            $states = json_decode(File::get($path), true);

            $formattedStates = collect($states)->map(function ($state) {
                return [
                    'country_id' => (int) $state['country_id'] ?? null,
                    'name'       => (string) $state['name'] ?? null,
                    'code'       => (string) $state['code'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            DB::table('states')->insert($formattedStates);
        }
    }
}
