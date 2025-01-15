<?php

namespace Webkul\Support\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('plugins/webkul/security/src/Data/currencies.json');

        if (File::exists($path)) {
            $currencies = json_decode(File::get($path), true);

            $currencies = collect($currencies)->map(function ($currency) {
                $currency['iso_numeric'] = (int) ($currency['iso_numeric'] ?? null);
                $currency['decimal_places'] = (int) ($currency['decimal_places'] ?? null);
                $currency['rounding'] = (float) ($currency['rounding'] ?? 0.00);
                $currency['active'] = (bool) ($currency['active'] ?? true);
                $currency['created_at'] = now();
                $currency['updated_at'] = now();

                return $currency;
            })->toArray();

            DB::table('currencies')->insert($currencies);
        }
    }
}
