<?php

namespace App\Http\Controllers;

use App\Branch;
use Config;
use Auth;

use Illuminate\Http\Request;

class BranchController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     * 
     */
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
        $branches = Branch::latest()->get();

        if(strtolower(Auth::user()->role) == 'admin'){
            return view('branches')->with('branches', $branches);
        }else{
            return abort(403);
        }
        
    }

    /**
     * View all branches 
     * 
     */
    public function all_branches()
    {
        $branches = Branch::all();

        return response()->json($branches, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branch = new Branch();

        return view('branches.create', compact('branch'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $branch = new Branch();
        
        $branch->setConnection('mysql2');

        $branch->name = $request->name;
        $branch->location = $request->location;

        $branch->save();

        return response()->json([
            'status' => (bool) $branch, 
            'data' => $branch,
            'message' => $branch ? 'Branch created successfully!' : 'Error creating branch'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        $branch = new Branch();

        return view('branches.show', compact('branch'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Branch $branch)
    {
        $branch = Branch::where('id', $request->id)->first();

        return view('branches.edit', compact('branch'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $branch)
    {
        $branch = new Branch();

        $branch->setConnection('mysql2');

        $branch->name = $request->name;
        $branch->location = $request->location;

        if($branch->update()){
            return response()->json([
                'message' => 'Branch updated successfully!',
                'data' => $branch,
                'error' => false
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error updating branch'
            ]);
        }
    }

    /**
     * Disable a branch by checking if the
     * branch is active or not
     * 
     */
    public function is_active(Request $request)
    {
        $branch = Branch::where('id', $request->id)->first();
        
        $branch->setConnection('mysql2');
    
        $isactive = $request->active;
        $branch->active = $isactive;

        if($branch->save()){
            return response()->json([
                'data' => $branch,
                'message' => 'Branch is updated'
            ]);
        }else{
            return response()->json([
                'message' => 'Error updating branch',
                'error' => true
            ]); 
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        $status = $branch->delete();

        $branch->setConnection('mysql2');

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Branch deleted successfully' : 'Error deleting branch'
        ]);

    }
}
