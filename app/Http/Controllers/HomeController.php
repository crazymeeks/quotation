<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    

    public function getIndex(Request $request)
    {
        return view('admin.pages.home');
    }
}
