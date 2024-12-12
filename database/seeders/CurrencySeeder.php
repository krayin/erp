<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Support\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Seed the application's database with currencies.
     */
    public function run(): void
    {
        $currencies = [
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
            'JPY' => 'Japanese Yen',
            'AUD' => 'Australian Dollar',
            'CAD' => 'Canadian Dollar',
            'CHF' => 'Swiss Franc',
            'CNY' => 'Chinese Yuan',
            'SEK' => 'Swedish Krona',
            'NZD' => 'New Zealand Dollar',
            'MXN' => 'Mexican Peso',
            'SGD' => 'Singapore Dollar',
            'HKD' => 'Hong Kong Dollar',
            'NOK' => 'Norwegian Krone',
            'KRW' => 'South Korean Won',
            'TRY' => 'Turkish Lira',
            'INR' => 'Indian Rupee',
            'RUB' => 'Russian Ruble',
            'BRL' => 'Brazilian Real',
            'ZAR' => 'South African Rand',
            'AED' => 'United Arab Emirates Dirham',
            'SAR' => 'Saudi Riyal',
            'MYR' => 'Malaysian Ringgit',
            'THB' => 'Thai Baht',
            'IDR' => 'Indonesian Rupiah',
            'PHP' => 'Philippine Peso',
            'VND' => 'Vietnamese Dong',
            'PLN' => 'Polish Zloty',
            'CZK' => 'Czech Koruna',
            'HUF' => 'Hungarian Forint',
            'ILS' => 'Israeli New Shekel',
            'CLP' => 'Chilean Peso',
            'COP' => 'Colombian Peso',
            'PKR' => 'Pakistani Rupee',
            'NGN' => 'Nigerian Naira',
            'EGP' => 'Egyptian Pound',
            'KWD' => 'Kuwaiti Dinar',
            'QAR' => 'Qatari Riyal',
            'OMR' => 'Omani Rial',
            'BHD' => 'Bahraini Dinar',
            'JOD' => 'Jordanian Dinar',
        ];

        Currency::query()->delete();

        $currencyData = collect($currencies)->map(function ($name, $code) {
            return [
                'code'       => $code,
                'name'       => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();

        Currency::insert($currencyData);
    }
}
