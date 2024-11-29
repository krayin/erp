<?php

namespace Webkul\Chatter\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Webkul\Chatter\Models\Task;
use Webkul\Security\Models\User;
use Webkul\Security\Traits\HasGroupPermissions;

class TaskPolicy
{
    use HandlesAuthorization;
    use HasGroupPermissions;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_task');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        if (! $user->can('view_task')) {
            return false;
        }

        return $this->hasAccess($user, $task, 'assignedTo');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_task');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        if (! $user->can('update_task')) {
            return false;
        }

        return $this->hasAccess($user, $task, 'assignedTo');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        if (! $user->can('delete_task')) {
            return false;
        }

        return $this->hasAccess($user, $task, 'assignedTo');
    }

    /**
     * Determine whether the user can bulk delete models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_task');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        if (! $user->can('force_delete_task')) {
            return false;
        }

        return $this->hasAccess($user, $task, 'assignedTo');
    }

    /**
     * Determine whether the user can permanently bulk delete models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_task');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        if (! $user->can('restore_task')) {
            return false;
        }

        return $this->hasAccess($user, $task, 'assignedTo');
    }

    /**
     * Determine whether the user can bulk restore models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_task');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Task $task): bool
    {
        if (! $user->can('replicate_task')) {
            return false;
        }

        return $this->hasAccess($user, $task, 'assignedTo');
    }

    /**
     * Determine whether the user can reorder tasks.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_task');
    }
}
