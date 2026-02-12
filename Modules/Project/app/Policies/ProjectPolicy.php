<?php

namespace Modules\Project\Policies;

use App\Models\User;
use Modules\Project\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can potentially view projects, 
        // but the list will be filtered by scope.
        return $user->hasAnyRole(['Superadmin', 'Manager', 'Team Leader', 'Employee']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        if ($user->hasAnyRole(['Superadmin', 'Manager'])) {
            return true;
        }

        // Team Leader & Employee: Must be assigned to the project
        // Or if they created it (for TLs, though creating is restricted currently)
        return $project->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Manager', 'Team Leader']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        if ($user->hasAnyRole(['Superadmin', 'Manager'])) {
            return true;
        }

        // Team Leaders can update projects they are assigned to as "Manager" or "Team Leader" logic?
        // Prompt says: "Can only manage tasks (WBS) for projects they are responsible for."
        // Using "Project details" update might be restricted. 
        // I will allow Team Leader to update if they are the creator or assigned as TL?
        // Let's stick to strict: Manager/Superadmin for Project Details.
        // But wait, "Team Leader... Can only manage tasks". 
        // So Project update is likely NO for TL.
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Manager']);
    }
}
