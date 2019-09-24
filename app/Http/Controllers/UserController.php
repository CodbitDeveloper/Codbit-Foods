<?php

namespace App\Http\Controllers;

use Auth;
use Config;

use App\User;
use App\Branch;

use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
         $this->middleware('auth');
         Config::set('database.connections.mysql2.database', session('db_name'));
    }


    /**
     * --------------------------------------
     * Present a page for viewing all users
     * --------------------------------------
     * 
     * @return view
     */
    public function index ()
      {
        if(strtolower(Auth::user()->role) == 'admin'){
          $users = User::all();
          $branches = Branch::all();
          return view('employees')->with('users', $users)->with('branches', $branches);
         }elseif (strtolower(Auth::user()->role) == 'manager') {
           $branches = Branch::where('id', Auth::user()->branch_id)->get();
           $users = User::where('branch_id', Auth::user()->branch_id)->get();
           return view('employees')->with('users', $users)->with('branches', $branches);
         }else{
           return abort(403);
         }
      }

      /**
       * ---------------
       * Get all users
       * ---------------
       * 
       * @return \Illuminate\Http\Response
       */
      public function all_users ()
      {
         $users = User::all();

         return response()->json([
               'data'    => $users,
               'success' => $users ? 1 : 0
            ], 200);
      }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();

        return view('users.create', compact('user'));
    }

      /**
       * ------------------------------
       * Register or create a new user
       * ------------------------------
       * 
       * @param  \Illuminate\Http\Request  $request
       * @return \Illuminate\Http\Response
       */
      public function store(Request $request)
      {
        $request->validate([
          'firstname' => 'required|string',
          'lastname' => 'required|string',
          'username' => 'required|string',
          'password' => 'required|min:6|max:100',
          'gender' => 'required',
          'phone' => 'required',
          'branch_id' => 'required',
          'role' => 'required'
        ]);

        $user = new User();

        $user->setConnection('mysql2');

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username.'@'.session('restaurant_domain');
        $user->password = bcrypt($request->password);
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->branch_id = $request->branch_id;
        $user->role = $request->role;

        if($user->save()){
          return response()->json([
            'data' => $user,
            'message' => 'User '.$user->username.' created successfully',
          ], 201);
        }else{
          return response()->json([
            'error' => 'Error creating user'
          ]);
        }
      }

      /**
       * --------------------
       * Update User Details
       * --------------------
       *
       * @param  \Illuminate\Http\Request  $request
       * @param  \App\User  $user
       * @return \Illuminate\Http\Response
       */
      public function update (Request $request, User $user)
      {
         $request->validate([
         'id' => 'required',
         'firstname' => 'required',
         'lastname' =>'required',
         'phone' => 'required',
         'branch_id' => 'required',
         'role' => 'required',
         'gender' => 'required'
      ]);

      $user = User::where('id', $request->id)->first();
      $error = true;
        
      $user->firstname = $request->firstname;
      $user->lastname = $request->lastname;
      $user->phone = $request->phone;
      $user->branch_id = $request->branch_id;
      $user->role = $request->role;
      $user->gender = $request->gender;

      if($user->update()){
        $status = false;
      }
       
      return response()->json([
         'error' => $status,
         'message' => !$status ? 'User account updated!' : 'Could not update user'
         ]);
      }

      /**
       * ----------------------------------------
       * Present a page for editing user details
       * -----------------------------------------
       * 
       * @return view
       */
      public function edit (Request $request)
      {
         $user = User::where('id', $request->id)->first();

         return view('users.edit', compact('user'));
      }


      /**
       * ------------------------------------------------------------------
       * Check the status of the user to see if the user is active or not.
       * -------------------------------------------------------------------
       * 
       * @param  \Illuminate\Http\Request  $request
       * @return \Illuminate\Http\Response
       */
      public function is_active(Request $request)
      {
         $user = User::where('id', $request->user_id)->first();

         $isactive     = $request->active;
         $user->active = $isactive;

         if ($user->save()){
            return response()->json([
                  'error' => false,
                  'data'    => $user,
                  'message' => 'User is updated'
               ]);
         }else{
            return response()->json([
                  'message' => 'Error updating user',
                  'error'   => true
               ]);
         }
      }

      /**
       * ----------------------------------
       * Check if a username already exist
       * -----------------------------------
       *
       * @param  \Illuminate\Http\Request  $request
       * @return bool
       */
      public function isAvailable (Request $request)
      {
         $user = User::where('username', '=', $request->username)->get();

         if (count($user) > 0){
            return true;
         }else{
            return false;
         }
      }


      /**
       * -----------------------
       * Delete User's account
       * -----------------------
       *
       * @param  \App\User  $user
       * @return \Illuminate\Http\Response
       */

      public function destroy (User $user)
      {
         $status = $user->delete();

         return response()->json([
               'status'  => $status,
               'message' => $status ? 'User Deleted!' : 'Error Deleting User'
            ]);
      }

      /**
       * -------------------------
       * Get current user details 
       * -------------------------
       * 
       * @return \Illuminate\Http\Response
       */
      public function getUser ()
      {
         $user = Auth::guard('api')->user();

         // dd($user);

         return response()->json([
               'data'    => $user,
               'message' => 'Current User Details Retrieved Successfully'
            ]);
      }

      /**
       * --------------------------------
       * Present a page for user profile
       * --------------------------------
       * 
       * @return view
       */
      public function myAccount()
      {
        return view('my-account');
      }
}
