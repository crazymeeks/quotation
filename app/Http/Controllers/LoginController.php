<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    
    public function index()
    {
        return view('admin.pages.login.index');
    }

    public function postLogout(Request $request)
    {
        $request->session()->forget('auth');
    }

    /**
     * Login
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(Request $request)
    {
        
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::whereUsername($request->username)->first();

        if ($user) {
            return $this->processLogin($user, $request);
        }

        return redirect()->back()->with('error', 'Invalid username or password.');

    }

    /**
     * Process login
     *
     * @param \App\Models\User $user
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function processLogin(User $user, Request $request)
    {
        if (Hash::check($request->password, $user->password)) {
            $request->session()->put('auth', $user);
            return redirect()->route('home');
        }
        return redirect()->back()->with('error', 'Invalid username or password.');
    }
}
