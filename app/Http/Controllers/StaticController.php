<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller
{
    public const STATIC_PAGES = ['about-us', 'contacts', 'faq', 'landing'];

    public static function getStaticPages()
    {
        return self::STATIC_PAGES;
    }

    public function show(Request $request)
    {

        return response()->view('static.' . $request->path());
    }
}
?>