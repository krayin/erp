<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Support\Database\Seeders\DatabaseSeeder as SupportDatabaseSeeder;
use Webkul\Security\Database\Seeders\DatabaseSeeder as SecurityDatabaseSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SecurityDatabaseSeeder::class,
            SupportDatabaseSeeder::class,
        ]);
    }
}
