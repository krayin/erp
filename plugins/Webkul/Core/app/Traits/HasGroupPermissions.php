<?php

namespace Webkul\Core\Traits;

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
        return $user->roles->permissions->contains('type', 'global');
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
        // Dynamically fetch the owner based on the specified attribute
        $owner = $model->{$ownerAttribute};

        return $user->roles->permissions->contains('type', 'group') &&
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

        return $user->roles->permissions->contains('type', 'individual') &&
            $owner && $owner->id === $user->id;
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
