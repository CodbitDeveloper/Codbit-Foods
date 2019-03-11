<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Config;
use Tymon\JWTAuth\Facades\JWTAuth; 

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
        Config::set('auth.providers.users.model', \App\Admin::class);
    }

    public function showLoginForm()
    {
        return view('auth.admin-login');
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
            //if successful, then redirect to intended location
            $token = JWTAuth::attempt($credentials);

            return \redirect()->intended(route('admin.dashboard'));

            /*return response()->json([
                'data' => Auth::guard('admin')->user(),
                'access_token' => $token
            ]);*/
        }
        //if unsuccessful, the redirect back to login page with the form data
        return redirect()->back()
            ->withInput($request->only('username'));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin');
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
