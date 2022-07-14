<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        return view('pages.dashboard');
    }

    public function welcome()
    {
        return view('welcome');
    }
}
