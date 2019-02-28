<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index ()
      {
        $users = User::all();

        if(strtolower(Auth::user()->role) == 'admin'){
          return view('users.index')->with('users', $users);
         }else{
           return abort(403);
         }
      }

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
       * Register or create a new user
       *
       * @param [string] name
       * @param [string] username
       * @param [string] password
       * @param [string] password_confirmation
       * @param [string] message
       */
      public function store(Request $request)
      {
        $request->validate([
          'firstname' => 'required|string',
          'lastname' => 'required|string',
          'username' => 'required|string|unique:users',
          'password' => 'required|confirmed|min:6|max:100',
          'gender' => 'required',
          'phone' => 'required',
          'branch_id' => 'required',
          'role' => 'required'
        ]);

        $user = new User();

        $user->setConnection('mysql2');

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->branch_id = $request->branch_id;
        $user->role = $request->role;

        if($user->save()){
          return response()->json([
            'data' => $user,
            'message' => 'Successfully created user',
          ], 201);
        }else{
          return response()->json([
            'error' => 'Error creating user'
          ]);
        }
      }

      /**
       * Update User Details
       *
       * @return [json] user object
       */
      public function update (Request $request, User $user)
      {
         $user = User::where('id', $request->id)->first();
         $status = true;

         $request->validate([
             'firstname' => 'required',
             'lastname' =>'required',
             'username' => 'required',
             'phone' => 'required'
         ]);
         
         if(request('password_reset') == 'yes'){
            if(Hash::check(request('old_password'), $user->password)){
                $user->password = bcrypt(request('new_password'));
            }else{
                return response()->json([
                    'error' => true,
                    'message' => 'The old password you provided is wrong'
                ]);
            }
        }
        
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->phone = $request->phone;

        if($user->update()){
            $status = false;
        }
       
        return response()->json([
            'error' => $status,
            'message' => !$status ? 'User Updated Successfully!' : 'Could not update user'
            ]);
      }

      /**
       * Edit user details
       */
      public function edit (Request $request)
      {
         $user = User::where('id', $request->id)->first();

         return view('users.edit', compact('user'));
      }


      /**
       * Check the status of the user to see
       * if the user is active or not.
       */
      public function is_active (Request $request)
      {
         $user = User::where('id', $request->id);

         $isactive     = $request->active;
         $user->active = $isactive;

         if ($user->save()){
            return response()->json([
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
       * Check if a username already exist
       *
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
       * Delete User
       *
       * @return [json] user object
       */

      public function destroy (User $user)
      {
         $status = $user->delete();

         return response()->json([
               'status'  => $status,
               'message' => $status ? 'User Deleted!' : 'Error Deleting User'
            ]);
      }

      public function getUser ()
      {
         $user = Auth::guard('api')->user();

         // dd($user);

         return response()->json([
               'data'    => $user,
               'message' => 'Current User Details Retrieved Successfully'
            ]);
      }
}
