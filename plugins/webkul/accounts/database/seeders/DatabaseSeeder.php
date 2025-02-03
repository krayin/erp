<?php

namespace Webkul\Account\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PaymentTermSeeder::class,
            PaymentDueTermSeeder::class,
            IncotermSeeder::class,
            AccountTagSeeder::class,
            JournalSeeder::class,
            TaxGroupSeeder::class,
            TaxSeeder::class
        ]);
    }
}
