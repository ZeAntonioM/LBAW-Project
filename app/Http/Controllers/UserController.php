<?php

namespace App\Http\Controllers;


use App\Events\ProjectNotificationEvent;
use App\Models\Project;

use App\Models\ProjectNotification;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Appeal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
DB::enableQueryLog();
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $user_status =['admin','user'];
    public function searchUsers(Request $request)
    {

        $searchTerm = '%' . $request->input('query') . '%';

        $project = $request->input('project');
        $query = null;
        if ($project !== null) {
            $this->authorize('viewTeam', [User::class,Project::find($project)]);
            $query = Project::find($project)->users();
        }
        else{
            $this->authorize('viewAny',User::class);
            $query = User::query();
        }
        $users = $query->where(function ($query) use ($searchTerm) {
            $query->where('email', 'like', $searchTerm)
                ->orWhere('name', 'like', $searchTerm)->with('getProfileImage');
        });
        if($request->input('status') and in_array($request->input('status') ,$this->user_status)){
            if($request->input('status') ==='admin') $users->where('is_admin','=',true);
            else $users->where('is_admin','=',false);
        }
        if ($request->ajax())

            return response()->json($users->get());
        else {
            if($project ===null)
                return $users->paginate(10)->withQueryString();
        }
    }


    public function checkUserExists(Request $request, $email) {
        $user = User::where('email', $email)->first();

        return response()->json($user != null);
    }

    public function block(Request $request, User $user)
    {
        $this->authorize("block", $user);

        $user->is_blocked = true;
        $user->save();

        return redirect()->route('admin');
    }

    public function unblock(Request $request, User $user)
    {
        $this->authorize("block", $user);

        $user->is_blocked = false;
        $user->save();

        return redirect()->route('admin');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {

        $this->authorize("delete", $user);

        $request->validate([
            'password' => 'required|string|',
        ]);
        if (Hash::check($request->input('password'), $user->password)) {

            $user->delete();

            return redirect()->route('login')->with('delete_user_success', 'Your user account was deleted successfully');
        } else {
            return redirect()->back()->with('password', 'Incorrect password');
        }
    }
    public function getUserNotification(Request $request){
        $user = $request->user();
        if(!$user) abort(404, 'user not found');

        return response()->json(
            [
                'projectNotifications'=>$user->projectNotifications()->with('project')->get(),
                'taskNotifications' =>$user->taskNotifications()->with('task.project')->get(),
                'postNotifications'=>$user->postNotifications()->with('post.project')->get(),
                'inviteNotifications'=>$user->inviteNotifications()->with('project')->get(),
                'commentNotifications'=>$user->commentNotifications()->with('comment.task.project')->get()
            ]
        );
    }
    public function getUserTasks(Request $request){
        $user = $request->user();
        if(!$user) abort(404, 'user not found');
        return response()->json($user->tasks);
    }

}
