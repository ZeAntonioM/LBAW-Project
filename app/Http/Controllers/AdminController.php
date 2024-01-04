<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class AdminController extends Controller
{
    private $user_status =['admin','user'];
    public function show(Request $request){
        $this->authorize('view_admin',[User::class]);
        $query = $request->input('query');
        $users = User::query()->count();
        if( $query)
            return view('admin.users', ['users' => app(UserController::class)->searchUsers($request),'query'=>$query,'status'=>$request->input('status'),'registrations'=>$users] );

        $user_query =User::query();
        if($request->input('status') and in_array($request->input('status') ,$this->user_status)){
            if($request->input('status') ==='admin') $user_query->where('is_admin','=',true);
            else $user_query->where('is_admin','=',false);
        }
        return view('admin.users', ['users' => $user_query->withQueryString()->paginate(10),'query'=>$query,'status'=>$request->input('status'),'registrations'=>$users] );
    }

    public function create(){
        $this->authorize('create_admin',[User::class]);
        return view('admin.create_user');
    }


    public function store(Request $request)
    {

        $this->authorize('create_admin', [User::class]);

        $rules = [
            'name' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'max:100',
                'unique:users'
            ],
            'password' => 'required|min:8|max:255|confirmed',
            'is_admin' => 'required|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin,
        ]);
        return redirect()->route('admin');
    }

    public function showProjects(Request $request){
        $this->authorize('view_all_projects', Project::class);
        $projects = app(ProjectController::class)->search($request);
        return view('admin.projects',['projects'=>$projects,'query'=>$request->input('query'),'status'=>$request->input('status')]);
    }
}
