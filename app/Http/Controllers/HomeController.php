<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if($user){
            if($user->is_blocked ) return redirect()->route('blocked', ['user'=>$user]);
            if($user->is_admin) return redirect()->route('admin');

            if ($request->session()->has('message'))
                return redirect()->route('projects',['user'=>$user->id])->with('message', $request->session()->get('message'));
            else
                return redirect()->route('projects',['user'=>$user->id]);
        }

        if ($request->session()->has('message'))
            return view('static.landing')->with('message', $request->session()->get('message'));
        else
            return view('static.landing');
    }
}




?>