<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = Admin::all();

        return view('admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $admin = new Admin();

        return view('admin.create', compact($admin));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string|max:255|unique:admins',
            'password' => 'required|confirmed|min:6'
        ]);

        $admin = new Admin();

        $admin->firstname = $request->firstname;
        $admin->lastname = $request->lastname;
        $admin->username = $request->username;
        $admin->password = bcrypt($request->password);

        if($admin->save()){
            return response()->json([
                'data' => $admin,
                'message' => 'Admin successfully created'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error creating admin, sorry try again'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        $admin = new Admin();

        return view('admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $admin = Admin::where('id', $request->id)->first();
        $status = true;

        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => 'required'
        ]);
        
        if(request('password_reset') == 'yes'){
            if(Hash::check(request('old_password'), $admin->password)){
                $admin->password = bcrypt(request('new_password'));
            }else{
                return response()->json([
                    'error' => true,
                    'message' => 'The old password you provided is wrong'
                ]);
            }
        }
        
        $admin->firstname = $request->firstname;
        $admin->lastname = $request->lastname;
        $admin->username = $request->username;

        if($admin->update()){
            $status = false;
        }
       
        return response()->json([
            'error' => $status,
            'message' => !$status ? 'Admin Updated Successfully!' : 'Could not update admin'
            ]);
    }

    public function is_active(Request $request)
    {
        $admin = Admin::where('id', $request->id)->first();

        $isactive = $request->active;
        $admin->active = $isactive;

        if($admin->save()){
            return response()->json([
                'data' => $admin,
                'message' => 'Admin updated'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Admin could not be updated'
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
 