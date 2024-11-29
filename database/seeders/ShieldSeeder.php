<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"panel_user","guard_name":"web","permissions":[]}]';
        $directPermissions = '[{"name":"view_role","guard_name":"web"},{"name":"view_any_role","guard_name":"web"},{"name":"create_role","guard_name":"web"},{"name":"update_role","guard_name":"web"},{"name":"delete_role","guard_name":"web"},{"name":"delete_any_role","guard_name":"web"},{"name":"view_user","guard_name":"web"},{"name":"view_any_user","guard_name":"web"},{"name":"create_user","guard_name":"web"},{"name":"update_user","guard_name":"web"},{"name":"restore_user","guard_name":"web"},{"name":"restore_any_user","guard_name":"web"},{"name":"replicate_user","guard_name":"web"},{"name":"reorder_user","guard_name":"web"},{"name":"delete_user","guard_name":"web"},{"name":"delete_any_user","guard_name":"web"},{"name":"force_delete_user","guard_name":"web"},{"name":"force_delete_any_user","guard_name":"web"},{"name":"view_team","guard_name":"web"},{"name":"view_any_team","guard_name":"web"},{"name":"create_team","guard_name":"web"},{"name":"update_team","guard_name":"web"},{"name":"restore_team","guard_name":"web"},{"name":"restore_any_team","guard_name":"web"},{"name":"replicate_team","guard_name":"web"},{"name":"reorder_team","guard_name":"web"},{"name":"delete_team","guard_name":"web"},{"name":"delete_any_team","guard_name":"web"},{"name":"force_delete_team","guard_name":"web"},{"name":"force_delete_any_team","guard_name":"web"},{"name":"page_ManageUsers","guard_name":"web"},{"name":"view_field","guard_name":"web"},{"name":"view_any_field","guard_name":"web"},{"name":"create_field","guard_name":"web"},{"name":"update_field","guard_name":"web"},{"name":"restore_field","guard_name":"web"},{"name":"restore_any_field","guard_name":"web"},{"name":"replicate_field","guard_name":"web"},{"name":"reorder_field","guard_name":"web"},{"name":"delete_field","guard_name":"web"},{"name":"delete_any_field","guard_name":"web"},{"name":"force_delete_field","guard_name":"web"},{"name":"force_delete_any_field","guard_name":"web"},{"name":"view_task","guard_name":"web"},{"name":"view_any_task","guard_name":"web"},{"name":"create_task","guard_name":"web"},{"name":"update_task","guard_name":"web"},{"name":"restore_task","guard_name":"web"},{"name":"restore_any_task","guard_name":"web"},{"name":"replicate_task","guard_name":"web"},{"name":"reorder_task","guard_name":"web"},{"name":"delete_task","guard_name":"web"},{"name":"delete_any_task","guard_name":"web"},{"name":"force_delete_task","guard_name":"web"},{"name":"force_delete_any_task","guard_name":"web"}]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name'       => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name'       => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name'       => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
