<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    function Home(Request $request)
    {
        if (auth()->user()) {
            return redirect("dashbord");
        } else {
            return view("home");
        }
    }
}
