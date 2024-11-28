<?php

namespace Webkul\Core\Traits;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield as BaseHasPanelShield;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

trait HasPanelShield
{
    use BaseHasPanelShield;

    public static function bootHasPanelShield()
    {
        if (Utils::isPanelUserRoleEnabled()) {
            $panelUserRoleName = Utils::getPanelUserRoleName();

            $role = Role::firstOrCreate(['name' => $panelUserRoleName]);

            $permissions = Permission::all();

            $role->syncPermissions($permissions);

            static::retrieved(function ($user) use ($panelUserRoleName) {
                $user->assignRole($panelUserRoleName);
            });

            static::deleting(function ($user) use ($panelUserRoleName) {
                $user->removeRole($panelUserRoleName);
            });
        }
    }
}
