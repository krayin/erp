<?php

namespace Webkul\Support\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('plugins/webkul/security/src/Data/countries.json');

        if (File::exists($path)) {
            $countries = json_decode(File::get($path), true);

            $formattedCountries = collect($countries)->map(function ($country) {
                return [
                    'currency_id'    => (int) $country['currency_id'] ?? null,
                    'phone_code'     => (int) $country['phone_code'] ?? null,
                    'code'           => $country['code'] ?? null,
                    'name'           => $country['name'] ?? null,
                    'state_required' => (bool) $country['state_required'],
                    'zip_required'   => (bool) $country['zip_required'],
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            })->toArray();

            DB::table('countries')->insert($formattedCountries);
        }
    }
}
