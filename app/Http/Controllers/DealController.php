<?php

namespace App\Http\Controllers;

use App\Deal;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Config('database.connections.mysql2.database', session('db_name'));
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deals = Deal::all();

        return view('deals')->with('deals', $deals);
    }

    public function all_deal()
    {
        $deals = Deal::latest()->get();

        return response()->json([
            'data' => $deals,
            'error' => false
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $deal = new Deal();

        return view('deal-add', compact('deal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->hasFile('file')){
            $fileName = Utils::saveImageFromDz($request, 'file', 'img/deals');

            $deal = Deal::create([
               "title" => $request->title,
               "description" => $request->description,
               "image" => $fileName,
               "starts_at" => $request->starts_at,
               "expires_at" => $request->expires_at,
            ]);

            return response()->json([
               'status'  => (bool) $deal,
               'data'    => $deal,
               'id' => $deal->id,
               'message' => $deal ? 'Deal Created!' : 'Error Creating Deal'
            ]);
         }


         return response()->json(["status" => 'no-image']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function show(Deal $deal)
    {
        return response()->json($deal, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function edit(Deal $deal, Request $request)
    {
        //get the deal
        $deal = Deal::where('id', $request->id)->first();

        //show the edit form and pass the deal
        return view('deal_edit')->with('deal', $deal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deal $deal)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'starts_at' => 'required',
            'expires_at' => 'required'
        ]);

        $deal->title = $request->title;
        $deal->description = $request->description;
        $deal->starts_at = $request->starts_at;
        $deal->expires_at = $request->expires_at;
       
        if($request->hasFile('file')){
            $fileName = Utils::saveImageFromDz($request, 'file', 'img/deals');
            $deal->image = $fileName;
        }

        $status = $deal->update();

        return response()->json([
            'data' => $deal,
            'status' => $status,
            'message' => $status ? 'Deal Updated' : 'Error Updating Deal'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deal $deal)
    {
        $status = $deal->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Deal deleted' : 'Error Deleting Deal'
        ]);
    }
}
