<?php

namespace App\Http\Controllers;

use App\Delivery;
use App\Order;
use Illuminate\Http\Request;
use Config;
use Auth;

class DeliveryController extends Controller
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
        $request->validate([
            'order_id' => 'required',
            'dispatch_id' => 'required'
        ]);

        $delivery = new Delivery();

        $delivery->order_id = $request->order_id;
        $delivery->dispatch_id = $request->dispatch_id;

        if($delivery->save()){
            $order = Order::where('id', $request->order_id)->first();
            $order->is_delivered = 1;

            if($order->save()){
                return response()->json([
                    'data' => $delivery,
                    'error' => false,
                    'message' => 'Order assigned to a dispatch rider'
                ]);
            }else{
                return response()->json([
                    'error' => true,
                    'message' => 'Error setting is delivered'
                ]);
            }
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error assigning an order to a dispatch rider'
            ]); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $delivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit(Delivery $delivery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Delivery $delivery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Delivery $delivery)
    {
        //
    }
}
