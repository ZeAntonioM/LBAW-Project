<?php
 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;

use Illuminate\View\View;

class LoginController extends Controller
{

    /**
     * Display a login form.
     */
    public function showLoginForm(Request $request)
    {
        if (Auth::check())
            return redirect()->back();

        if ($request->session()->has('project') && $request->session()->has('userEmail')) {
            return view('auth.login')->with([
                'project' => $request->session()->get('project'),
                'userEmail' => $request->session()->get('userEmail'),
            ]);
        }
        else
            return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            if ($request->filled('project')) {
                $project = Project::find($request->input('project'));
                
                if ($project == null)
                    return redirect()->route('home')->with('message', ['error', 'The project you tried to join no longer exists']);
                else if ($project->users->contains(Auth::user())) {
                    return redirect()->route('home')->with('message', ['info', 'You are already in the project ' . $project->name]);
                }
                else {
                    $project->users()->attach(Auth::id());
                    return redirect()->route('home')->with('message', ['success', 'You have joined the project ' . $project->name]);
                }
            }
            else
                return redirect()->route('home');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email', 'project');
    }

    /**
     * Log out the user from application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')
            ->withSuccess('You have logged out successfully!');
    }

    public function auth(Request $request){
        return Auth::user();
    }
}
