<?php

namespace App\Http\Controllers;

use App\Dispatch;
use Config;
use Illuminate\Http\Request;

class DispatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Config::set('database.connections.mysql2.database', session('db_name'));
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dispatches = Dispatch::all();

        return view('dispatch')->with('dispatches', $dispatches);
    }

    /**
     * -------------------------
     * Get all dispatch riders
     * -------------------------
     * 
     * @return \Illuminate\Http\Response
     */
    public function all_dispatches()
      {
        $dispatches = Dispatch::all();

        return response()->json([
            'data' =>$dispatches,
        ], 200);
      }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dispatch = new Dispatch();

        return view('dispatch-create', compact('dispatch'));
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
            'lastname'  => 'required|string',
            'phone'     => 'required|string',
         ]);

         $dispatch = new Dispatch();
         
         $dispatch->firstname = $request->firstname;
         $dispatch->lastname  = $request->lastname;
         $dispatch->phone     = $request->phone;
         

         if($dispatch->save()){
            return response()->json([
                'error' => false,
                'data' => $dispatch,
                'message' => 'Dispatch Rider created successfully'
             ], 201);
         }else{
            return response()->json([
                'error' => true,
                'message' => 'Error creating dispatch rider'
             ]);
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function show(Dispatch $dispatch)
    {
        return response()->json($dispatch, 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function edit(Dispatch $dispatch, Request $request)
    {
        $dispatch = Dispatch::where('id', $request->id)->first();

        return view('dispatch-edit', compact('dispatch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dispatch $dispatch)
    {
        $request->validate([
            'id' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'phone' => 'required'
        ]);

        $dispatch = Dispatch::where('id', $request->id)->first();
        $error = true;

        $dispatch->firstname = $request->firstname;
        $dispatch->lastname = $request->lastname;
        $dispatch->phone = $request->phone;

        if($dispatch->update()){
            $status = false;
        }

        return response()->json([
            'error' => $status,
            'message' => !$status ? 'Dispatch Rider Updated' : 'Error updating dispatch rider'
        ]);
        
    }

    /**
     * ------------------------------------------------------------------
     * Check the status of the user to see if the user is active or not.
     * ------------------------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function is_active(Request $request)
    {
    $dispatch = Dispatch::where('id', $request->dispatch_id)->first();;

    $isactive = $request->active;
    $dispatch->active = $isactive;

    if($dispatch->save()){
        return response()->json([
            'error' => false,
            'data' => $dispatch,
            'message' => 'Dispatch Rider is updated'
        ]);
    }else{
        return response()->json([
            'message' => 'Nothing to update',
            'error' => true
        ]); 
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dispatch $dispatch)
    {
        $status = $dispatch->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Dispatch Rider Deleted!' : 'Error Deleting Dispatch Rider'
        ]);
    }
}
