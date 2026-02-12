<?php

namespace Modules\System\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SystemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user->hasRole('Superadmin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user): bool
    {
        return $user->hasRole('Superadmin');
    }
}
