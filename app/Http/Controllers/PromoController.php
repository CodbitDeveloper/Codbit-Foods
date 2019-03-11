<?php

namespace App\Http\Controllers;

use App\Promo;
use App\Item;
use App\Customer;
use Illuminate\Http\Request;

class PromoController extends Controller
{
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
        return view('add_promo-code');
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
            'name' => 'required',
            'code' => 'required', 
            'description' => 'required',
            'max_uses' => 'required',
            'max_uses_user' => 'required',
            'promo_amount' => 'required',
        ]);

        $promo = new Promo();

        $promo->name = $request->name;
        $promo->code = $request->code;
        $promo->description = $request->description;
        $promo->max_uses = $request->max_uses;
        $promo->max_uses_customer = $request->max_uses_customer;
        $promo->promo_amount = $request->promo_amount;
        $promo->create_date = $request->create_date;
        $promo->starts_at = $request->starts_at;
        $promo->expires_at = $request->expires_at;

        if($promo->save()){
            $items = json_decode($request->items, true);
            $customers = \json_decode($request->customers, true);

            $promo->items()->attach($items);
            $promo->customers()->attach($customers);

            return response()->json([

            ]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function edit(Promo $promo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Promo $promo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promo $promo)
    {
        //
    }
}
