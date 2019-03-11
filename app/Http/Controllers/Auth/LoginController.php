<?php

namespace App\Http\Controllers\Auth;

use DB;
use Auth;
use Config;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\User;
use App\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Config::set('database.connections.mysql2.database');
        $this->middleware('guest')->except('logout', 'userLogout');
    }
    
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    protected function credentials(Request $request)
    {
        $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)
            ? $this->username()
            : 'username';

        return [
            $field => $request->get($this->username()),
            'password' => $request->password,
            'active' => 1,
        ];
    }

    public function username()
    {
        return 'username';
    }

    public function userLogout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }

    public function login(Request $request)
    {
        $domain = '';
        $credentials = $request->only('username', 'password');
        
        if (($pos = strpos($request->username, "@")) !== FALSE) { 
            $domain = substr($request->username, $pos+1); 
        }

        $restaurant = Restaurant::where('domain', $domain)->first();
        
        if($restaurant!=null){
            config(["database.connections.mysql2.database" => $restaurant->DB_name]);
            config(["database.connections.mysql.database" => 'codbitfood']);
            DB::purge();

            $request->session()->put('db_name', $restaurant->DB_name);
        }else{  
            return $this->sendFailedLoginResponse($request);
        }       

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {

            $token = JWTAuth::attempt($credentials);

            $request->session()->put('restaurant_name', $restaurant->name);
            $request->session()->put('restaurant_domain', $restaurant->domain);
            $request->session()->put('restaurant_logo', $restaurant->logo);

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    
    protected function authenticated(Request $request)
    {
        $restaurant = Restaurant::where('domain', $request->domain)->first();
        $request->session()->put('restaurant', $restaurant);
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
