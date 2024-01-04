<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->is_admin || ($task->project->users->contains($user) && !$user->is_blocked);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project)
    {
        return !$user->isAdmin && ($project->users->contains($user) && !$user->is_blocked) && !$project->is_archived;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return ($task->project->user_id == $user->id || $task->assigned->contains($user)) && $task->status == 'open' && !$user->is_blocked && !$task->project->is_archived;
    }

    public function changeStatus(User $user, Task $task): bool
    {
        return ($task->project->user_id == $user->id || $task->assigned->contains($user)) && !$user->is_blocked && !$task->project->is_archived;
    }

    /**
     * Determine whether the user can comment on the model.
     */
    public function comment(User $user, Task $task): bool
    {
        return $task->project->users->contains($user) && !$user->is_blocked;
    }

    public function delete_comment(User $user,Comment $comment):bool
    {
        return $user->id == $comment->user_id;
    }
    public function edit_comment(User $user,Comment $comment):bool
    {
        return $user->id == $comment->user_id;
    }
}
