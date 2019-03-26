<?php

namespace App\Http\Controllers;

use App\Promo;
use App\Item;

use App\Customer;
use App\Category;

use Config;
use Illuminate\Http\Request;

class PromoController extends Controller
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
        $customers = Customer::all();
        $categories = Category::with('items')->get();

        return view('add-promotion', compact('customers', 'categories'));
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

        if(Promo::where('code', $request->code)->get()->count() > 0){
            return response()->json([
                'error' => true,
                'message' => 'The promo code specified has already been used'
            ]);
        }

        if($request->max_uses < -1 || $request->max_uses_user == 0){
            return response()->json([
                'error' => true,
                'message' => 'Invalid maximum uses specified'
            ]);
        }

        if($request->max_uses_user < -1){
            return response()->json([
                'error' => true,
                'message' => 'Invalid maximum uses per customer specified'
            ]);
        }

        $promo = new Promo();

        $promo->name = $request->name;
        $promo->code = $request->code;
        $promo->description = $request->description;
        $promo->max_uses = $request->max_uses;
        $promo->max_uses_customer = $request->max_uses_user;
        $promo->promo_amount = $request->promo_amount;
        $promo->starts_at = $request->starts_at;
        $promo->expires_at = $request->expires_at;

        if($promo->save()){
            if($request->all_customers == true){
                $items = Item::all();
                $items->pluck('id')->toArray();
            }else{
                $items = explode(',', $request->items);
            }

            if($request->all_items == true){
                $customers = Customer::all();
                $customers->pluck('id')->toArray();
            }else{
                $customers = explode(',', $request->customers);
            }
            
            $promo->items()->attach($items);
            $promo->customers()->attach($customers);

            return response()->json([
                'error' => false,
                'message' => 'Promo created'
                
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'Could not create the promo'
        ]);
        
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
