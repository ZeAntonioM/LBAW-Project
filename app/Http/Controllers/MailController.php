<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MailModel;
use Illuminate\Support\Facades\Mail;
class MailController extends Controller
{
    public static function send($mailData) {

        Mail::to($mailData['email'])->send(new MailModel($mailData));
    }

}
