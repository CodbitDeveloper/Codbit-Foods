<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Item;
use App\Category;

use Illuminate\Http\Request;

use Config;
use Auth;

class SettingController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }

    public function search(Request $request){
        $query = $request->q;
        $items = Item::with('category')->where('name', 'LIKE', '%'.$request->q.'%')
        ->orWhere('description', 'LIKE', '%'.$request->q.'%')
        ->orWhereHas('category', function($q) use ($request){
            $q->where('name', 'LIKE', '%'.$request->q.'%');
        })->get();
        
        return view('search', compact('items', 'query'));
    }

    public function reports(Request $request){
        return view('reports');
    }
}
