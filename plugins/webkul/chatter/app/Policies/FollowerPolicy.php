<?php

namespace Webkul\Chatter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webkul\Chatter\Models\Follower;
use Webkul\Support\Models\User;

class FollowerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_follower');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Follower $follower): bool
    {
        return $user->can('view_follower');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_follower');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Follower $follower): bool
    {
        return $user->can('update_follower');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Follower $follower): bool
    {
        return $user->can('delete_follower');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_follower');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Follower $follower): bool
    {
        return $user->can('force_delete_follower');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_follower');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Follower $follower): bool
    {
        return $user->can('restore_follower');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_follower');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Follower $follower): bool
    {
        return $user->can('replicate_follower');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_follower');
    }
}
