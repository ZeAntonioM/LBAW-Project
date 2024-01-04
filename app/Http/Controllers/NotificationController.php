<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function seenProjectNotification(){
        $user = Auth::user();

        if(!$user) return response()->json(["Error"=>'User not found']);
        $notifications =$user->projectNotifications()->where('seen','=','false')->get();
        foreach ($notifications as $notification) {
            $notification->seen = True;
            $notification->save();
        }
        return response()->json();

    }
    public function seenTaskNotification(){
        $user = Auth::user();

        if(!$user) return response()->json(["Error"=>'User not found']);
        $notifications =$user->taskNotifications()->where('seen','=','false')->get();
        foreach ($notifications as $notification) {
            $notification->seen = True;
            $notification->save();
        }
        return response()->json();

    }
    public function seenInviteNotification(){
        $user = Auth::user();

        if(!$user) return response()->json(["Error"=>'User not found']);
        $notifications =$user->inviteNotifications()->where('seen','=','false')->get();
        foreach ($notifications as $notification) {
            $notification->seen = True;
            $notification->save();
        }
        return response()->json();

    }
    public function seenForumNotification(){
        $user = Auth::user();

        if(!$user) return response()->json(["Error"=>'User not found']);
        $notifications =$user->postNotifications()->where('seen','=','false')->get();
        foreach ($notifications as $notification) {
            $notification->seen = True;
            $notification->save();
        }
        return response()->json();

    }
    public function seenCommentNotification(){
        $user = Auth::user();

        if(!$user) return response()->json(["Error"=>'User not found']);
        $notifications =$user->commentNotifications()->where('seen','=','false')->get();
        foreach ($notifications as $notification) {
            $notification->seen = True;
            $notification->save();
        }
        return response()->json();

    }
}
