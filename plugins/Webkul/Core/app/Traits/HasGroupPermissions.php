<?php

namespace Webkul\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Enums\PermissionType;
use Webkul\Core\Models\User;

trait HasGroupPermissions
{
    /**
     * Check if the user has global access to any resource.
     */
    protected function hasGlobalAccess(User $user): bool
    {
        return $user->resource_permission === PermissionType::GLOBAL->value;
    }

    /**
     * Check if the user has group access to resources of users in the same group.
     */
    protected function hasGroupAccess(User $user, Model $model, string $ownerAttribute = 'user'): bool
    {
        $hasGroupAccess = $user->resource_permission === PermissionType::GROUP->value;

        if (! $hasGroupAccess) {
            return false;
        }

        $owner = $model->{$ownerAttribute};

        if (
            $owner
            && $hasGroupAccess
        ) {
            return $owner->teams->intersect($user->teams)->isNotEmpty();
        }

        return false;
    }

    /**
     * Check if the user has individual access to their own resources only.
     */
    protected function hasIndividualAccess(User $user, Model $model, $ownerAttribute = 'user'): bool
    {
        $hasIndividualAccess = $user->resource_permission === PermissionType::INDIVIDUAL->value;

        if (! $hasIndividualAccess) {
            return false;
        }

        $owner = $model->{$ownerAttribute};

        return $hasIndividualAccess
            && $owner
            && $owner->id === $user->id;
    }

    /**
     * Main access method that combines all permissions.
     */
    protected function hasAccess(User $user, Model $model, string $ownerAttribute = 'user'): bool
    {
        return $this->hasGlobalAccess($user)
            || $this->hasGroupAccess($user, $model, $ownerAttribute)
            || $this->hasIndividualAccess($user, $model, $ownerAttribute);
    }
}
