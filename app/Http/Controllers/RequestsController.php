<?php

namespace App\Http\Controllers;

use App\Requests;
use App\Admin;
use App\Notifications\RequestReceived;
use Illuminate\Http\Request;

class RequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = Requests::all();

        return view('admin.requests', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $requests = new Requests();

        return view('admin.create_request', compact('requests'));
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
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'contact_number' => 'required'
        ]);

        $requests = new Requests();

        $requests->name = $request->name;
        $requests->email = $request->email;
        $requests->contact_number = $request->contact_number;

        if($requests->save()){
            $admins = Admin::all();

            foreach($admins as $admin){
                $admin->notify(new RequestReceived($requests));
            }

            return response()->json([
                'data' => $requests,
                'message' => 'Request successfully sent'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error sending request'
            ]);  
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Requests  $requests
     * @return \Illuminate\Http\Response
     */
    public function show(Requests $requests)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Requests  $requests
     * @return \Illuminate\Http\Response
     */
    public function edit(Requests $requests)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Requests  $requests
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Requests $requests)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Requests  $requests
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requests $requests)
    {
        //
    }
}
