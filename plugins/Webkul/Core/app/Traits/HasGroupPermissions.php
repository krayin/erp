<?php

namespace Webkul\Core\Traits;

use Webkul\Core\Enums\PermissionType;

trait HasGroupPermissions
{
    /**
     * Check if the user has global access to any resource.
     *
     * @param  \Webkul\Core\Models\User  $user
     * @return bool
     */
    protected function hasGlobalAccess($user)
    {
        return $user->resource_permission === PermissionType::GLOBAL->value;
    }

    /**
     * Check if the user has group access to resources of users in the same group.
     *
     * @param  \Webkul\Core\Models\User  $user
     * @param  mixed  $model
     * @param  string  $ownerAttribute
     * @return bool
     */
    protected function hasGroupAccess($user, $model, $ownerAttribute = 'user')
    {
        $owner = $model->{$ownerAttribute};

        return $user->resource_permission === PermissionType::GROUP->value &&
            $owner && $user->group_id === $owner->group_id;
    }

    /**
     * Check if the user has individual access to their own resources only.
     *
     * @param  \Webkul\Core\Models\User  $user
     * @param  mixed  $model
     * @param  string  $ownerAttribute
     * @return bool
     */
    protected function hasIndividualAccess($user, $model, $ownerAttribute = 'user')
    {
        $owner = $model->{$ownerAttribute};

        return $user->resource_permission === PermissionType::INDIVIDUAL->value && $owner && $owner->id === $user->id;
    }

    /**
     * Main access method that combines all permissions.
     *
     * @param  \Webkul\Core\Models\User  $user
     * @param  mixed  $model
     * @param  string  $ownerAttribute
     * @return bool
     */
    protected function hasAccess($user, $model, $ownerAttribute = 'user')
    {
        return $this->hasGlobalAccess($user) ||
            $this->hasGroupAccess($user, $model, $ownerAttribute) ||
            $this->hasIndividualAccess($user, $model, $ownerAttribute);
    }
}