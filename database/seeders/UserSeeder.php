<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Webkul\Core\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name'              => 'Example',
            'email'             => 'admin@example.com',
            'password'          => bcrypt('admin123'),
            'email_verified_at' => now(),
            'remember_token'    => Str::random(10),
        ]);

        $user->assignRole('panel_user');
    }
}
