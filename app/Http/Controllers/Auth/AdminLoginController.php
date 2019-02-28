<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    public function login(Request $request)
    {
        //Validate the form data
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6'
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'active' => 1,
        ];

        //Attempt to log the user in
        if(Auth::guard('admin')->attempt($credentials)){
            //dd(Auth::guard('admin')->user()->username);
            //if successful, then redirect to intended location
            return \redirect()->intended(route('admin.dashboard'));
        }
        //if unsuccessful, the redirect back to login page with the form data
        return redirect()->back()
            ->withInput($request->only('username'));
    }
}
