<?php 
namespace App\Http\Controllers\Auth; 

use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Correct import for DB
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail; // Correct import for Mail
use Illuminate\Support\Facades\Hash; // Correct import for Hash
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Date;
use App\Models\Project;
use Illuminate\Support\Facades\Log;
  
class ForgotPasswordController extends Controller
{
    
      public function showForgetPasswordForm()
      {
         return view('auth.forgot-password');
      }
  
    
      public function submitForgetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|',
          ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->with('error','Email not found');
        }

        $lastResetToken = DB::table('password_reset_tokens')->where('email', $request->email)->orderBy('created_at', 'desc')->first();

        if ($lastResetToken && Date::parse($lastResetToken->created_at)->diffInMinutes(now()) < 5) {
            return redirect()->back()->with('error', 'Password reset request already sent within the last 5 minutes.');
        }
          $token = Str::random(64);
  
          DB::table('password_reset_tokens')->insert([
              'email' => $request->email, 
              'token' => $token, 
              'created_at' => Carbon::now()
            ]);
          $mailData =[
            'email' => $request->email,
            'token' => $token,
            'subject' => "Recover password",
          ];
          
          MailController::send($mailData);
          
          return back()->with('message', 'We have e-mailed your password reset link!');
      }
    
      public function showResetPasswordForm(Request $request,$token) { 
        $updatePassword = DB::table('password_reset_tokens')->where(['token' => $token])->first();
        if(!$updatePassword)
            abort(404, 'Token not found.');
        return view('auth.reset-password', ['email'=>$updatePassword->email,'token' => $token]);
      }
  
   
      public function submitResetPasswordForm(Request $request)
      { 
        if(!$request){
            abort(404, 'Request not found.');
        }
          $request->validate([
              'email' => 'required|email|',
              'password' => 'required|string|min:8|',
              'password_confirmation' => [
                'required',
                'min:8',
                Rule::in([$request->input('password')]),
            ],
          ]);
  
          $updatePassword = DB::table('password_reset_tokens')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
  
          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }
  
          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);
 
          DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();
  
          return redirect('/login')->with('message', 'Your password has been changed successfully!');
      }

    public function accept_invite(Request $request, $token) {
        $invite = DB::table('invites')->where(['token' => $token])->first();

        if(!$invite)
            return redirect()->route('home')->with('message', ['error', 'Link to join the project has expired']);

        $user = User::where('email', $invite->email)->first();
        $project = Project::find($invite->project_id);

        DB::table('invites')->where('email', $invite->email)->delete();

        if (Auth::check()) {
            if ($project->users->contains($user)) {
                return redirect()->route('projects')->with('message', ['info', 'You are already in the project ' . $project->name]);
            }
            else {
                if (Auth::user()->is_admin)
                    return redirect()->route('home')->with('message', ['error', 'Administrators cannot join projects']);
                $project->users()->attach(Auth::id());
                return redirect()->route('projects')->with('message', ['success', 'You have joined the project ' . $project->name]);
            }
        }
        else {
            if ($user != null) {
                if ($user->is_admin)
                    return redirect()->route('home')->with('message', ['error', 'Administrators cannot join projects']);
                return redirect()->route('login')->with(['project' => $project->id, 'userEmail' => $invite->email]);
            }
            else {
                return redirect()->route('register')->with(['project' => $project->id, 'userEmail' => $invite->email]);
            }
        }
    }
}