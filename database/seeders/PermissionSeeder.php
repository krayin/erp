<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = collect(FilamentShield::getAllResourcePermissions())
            ->keys()
            ->map(fn($resource) => [
                'name'       => $resource,
                'guard_name' => 'web',
            ])
            ->toArray();

        DB::table('permissions')->insertOrIgnore($resources);

        $adminRole = Role::updateOrCreate([
            'name'       => 'panel_user',
            'guard_name' => 'web',
        ]);

        $adminRole->syncPermissions(Permission::pluck('name'));
    }
}
