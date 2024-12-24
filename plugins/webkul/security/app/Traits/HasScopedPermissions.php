<?php

namespace Webkul\Security\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Enums\PermissionType;
use Webkul\Security\Models\User;

trait HasScopedPermissions
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
            $userTeamIds = $user->teams->pluck('id');

            if ($owner instanceof Collection) {
                $ownerTeamIds = $owner->pluck('teams')->flatten()->pluck('id');
            } else {
                $ownerTeamIds = $owner->teams->pluck('id');
            }

            return $ownerTeamIds->intersect($userTeamIds)->isNotEmpty();
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
            && $owner instanceof Collection ? $owner->pluck('id')->contains($user->id) : $owner->id === $user->id;
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
