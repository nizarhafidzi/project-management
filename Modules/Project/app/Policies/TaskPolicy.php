<?php

namespace Modules\Project\Policies;

use App\Models\User;
use Modules\Project\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Filtered by Project
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        if ($user->hasAnyRole(['Superadmin', 'Manager', 'Team Leader'])) {
            return $user->can('view', $task->project);
        }

        // Employee: Only their assigned tasks
        if ($user->hasRole('Employee')) {
            return $task->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Team Leaders and above can create/manage WBS
        return $user->hasAnyRole(['Superadmin', 'Manager', 'Team Leader']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->hasAnyRole(['Superadmin', 'Manager'])) {
            return true;
        }

        if ($user->hasRole('Team Leader')) {
            // Can update if they have access to the project
            return $user->can('view', $task->project);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Manager', 'Team Leader']);
    }
}
