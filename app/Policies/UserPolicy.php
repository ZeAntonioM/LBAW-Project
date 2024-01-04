<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function viewTeam(User $user,Project $project): bool
    {
        return ($user->projects->pluck('id')->contains($project->id) || $user->is_admin) && !$user->is_blocked;

    }


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $user_profile): bool
    {
        return ($user->is_admin || $user_profile->id===$user->id) && !$user_profile->is_blocked;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return ($user->id === $model->id || $user->is_admin) && !$user->is_blocked;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return ($model == $user || $user->is_admin) && !$user->is_blocked;
    }

    /**
     * Determine whether the user can create admins.
     */
    public function create_admin(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can view admins.
     */
    public function view_admin(User $user): bool
    {
        return $user->is_admin;
    }
    
    /**
     * Determine whether the user can block users.
     */
    public function block(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can unblock users.
     */
    public function unblock(User $user): bool
    {
       return $user->is_admin;
    }

    public function showAppealForUnblock(User $user): bool
    {
        return $user->is_blocked;
    }

    public function storeAppealForUnblock(User $user): bool
    {
        return $user->is_blocked;
    }
    
    public function view_appeals(User $user): bool
    {
        return $user->is_admin;
    }

}
