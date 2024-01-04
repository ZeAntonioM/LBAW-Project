<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class PostPolicy
{
    
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        
        return $user->is_admin || ($post->project->users->contains($user) && !$user->is_blocked);

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        return $project->users->contains($user) && !$user->is_blocked;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user->id && !$user->is_blocked;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user->id && !$user->is_blocked;
    }

}
