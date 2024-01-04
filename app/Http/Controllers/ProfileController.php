<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
class ProfileController extends Controller
{

        public function show(){
            $user = Auth::user();
            if(!$user) abort(404, 'User not logged in.');
            return redirect()->route('profile',['user'=>$user]);
        }
       
        public function showProfile(User $user): View
        {   

            $this->authorize('view', $user);

            if (!$user) {
                abort(404, 'User profile page not found.');
            }
            $tasks = $user->tasks;
            $taskList = [];
            foreach ($tasks as $task) {
                $taskList[] = ['task' => $task, 'project' => $task->project];
            }
            
            usort($taskList, function ($a, $b) {
                $deadlineA = $a['task']->deadline;
                $deadlineB = $b['task']->deadline;
                return ($deadlineA > $deadlineB) ? -1 : 1;
            });
            $image = self::getImage($user);
            return view('profile_pages.profile', [
                'user' => $user,
                'image' => $image,
                'tasks' => $taskList,
            ]);
        }
        

        public function showEditProfile(User $user): View
        {
            if (!$user) {
                abort(404, 'User profile page not found.');
            }
        
            $this->authorize('update', $user);

            $image = self::getImage($user);
            return view('profile_pages.edit-profile', [
                'user' => $user,
                'image' =>$image,
            ]);
        }
        
        public function showDelete(User $user): View
        {
            if (!$user) {
                abort(404, 'User profile page not found.');
            }
            return view('profile_pages.delete', [
                'user' => $user,
            ]);
        }
    
    public function updateProfile(Request $request, User $user)
    {

        $rules = [
            'name' => 'required|string|max:20',
            'old_password' => 'required_with:new_password|min:8',
            'new_password' => 'min:8|max:255',
        ];
    
        $customErrors = [
            'name.max' => 'The name must not exceed 20 characters.',
            'old_password.required_with' => 'Please provide the old password when updating the password.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ];
    
        $validator = Validator::make($request->all(), $rules, $customErrors);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->authorize('update', $user);
    
        $user->name = $request->name;
    
        if ($request->filled('new_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->with('error', 'The old password is incorrect');
            }

            $user->password = Hash::make($request->new_password);
        }
        $user->save();
        return redirect()->route('profile', ['user' => $user]);

    }
    private static function validRequest(Request $request):bool{
        if($request->hasFile("file") && $request->file("file")->isValid()){ 
            return true;
        }
        return false;
    }
    public static function getImage(User $user){
        $fileName = $user->file;
      
        if (empty($fileName)) {
            $user->file = 'default_user.jpg';
            $user->save();
        }

        return Storage::url('/files'. '/user'. '/' .$user->file);
    }
    public function updateProfileImage(Request $request, User $user){
            if(self::validRequest($request)){
                // Hash and Get image
                $file = $request->file('file');
                $hashedFilename = $file->hashName();
                // Save image and delte image if not default
                if($user->file != 'default_user.jpg')
                    self::deleteImage($user);
                $user->file = $hashedFilename;
                $user->save();
                Storage::putFileAs('user', new File($request->file('file')),$user->file);
                return redirect()->back()->with('success_image','Profile picture uploaded successfully');    
            }
            return redirect()->back()->with('error_image','Cant submit picture');
    }
    private static function deleteImage(User $user){
        Storage::disk('proj_planner_files')->delete('/user'.'/'.$user->file);
    }

    public static function deleteProfileImage(User $user){
        if ($user->file == 'default_user.jpg'){
            return redirect()->back()->with('delete_error_image', 'Nothing to delete');
        }
        if ($user && !empty($user->file) ) {
            self::deleteImage($user);
        }
        $user->file = 'default_user.jpg';
        $user->save();
        return redirect()->back()->with('delete_success_image','Delete sucessfull');
    }
}