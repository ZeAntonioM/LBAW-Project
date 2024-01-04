<?php
namespace App\Policies;
use App\Models\Project;
use App\Models\User;

class FilePolicy
{   
    public function download(User $user,Project $project):bool{
        $users = $project->users()->get()->toArray();
        $usersIds = array();
        foreach($users as $a_user) {
            array_push($usersIds, $a_user['id']);
        }
        return (in_array($user->id, $usersIds));
    }
    public function delete(User $user,Project $project):bool{
        $users = $project->users()->get()->toArray();
        $usersIds = array();
        foreach($users as $a_user) {
            array_push($usersIds, $a_user['id']);
        }
        return (in_array($user->id, $usersIds)  && !$project->is_archived);
    }
    public function upload(User $user,Project $project):bool{
        $users = $project->users()->get()->toArray();
        $usersIds = array();
        foreach($users as $a_user) {
            array_push($usersIds, $a_user['id']);
        }
        return (in_array($user->id, $usersIds)  && !$project->is_archived);
    }
    public function deleteAll(User $user,Project $project):bool{
        $users = $project->users()->get()->toArray();
        $usersIds = array();
        foreach($users as $a_user) {
            array_push($usersIds, $a_user['id']);
        }
        $coordinator = $project->user_id;
        return (in_array($user->id, $usersIds) && $user->id == $coordinator && !$project->is_archived);
    }

    public function downloadAll(User $user,Project $project):bool{
        $users = $project->users()->get()->toArray();
        $usersIds = array();
        foreach($users as $a_user) {
            array_push($usersIds, $a_user['id']);
        }
        return (in_array($user->id, $usersIds));
    }
}