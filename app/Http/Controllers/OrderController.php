<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Config;

class OrderController extends Controller
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
        $orders = Order::all();
        
        return view('orders.index',compact('orders'));
    }

    /**
     * Get orders with order items
     */
    public function order_with_item()
    {
        $orders = Order::with(['items', 'customer'])->get();
        
        return $orders->toJson();
    }



    public function allOrders()
    {
        $orders = Order::latest()->get();

        return $orders->toJson();
    }
    
/**
     * Get all the latest orders
     * 
     */
    public function latestOrders(Request $request)
    {
        $orders = Order::with(['items', 'customer'])->where('id', '>', $request->id)->get();

        return response()->json([
            'data' => $orders,
            'message' => $orders ? 1 : 0
        ]);
    }

    public function deliverOrder(Order $order, Request $request)
    {
        $order = Order::where('id', $request->id)->first();

        $delivered = $request->is_delivered;
        $order->is_delivered = $delivered;

        $order->save();

        return response()->json([
            'data' => $order,
            'message' => $order ? 'Order to be delivered!' : 'Error delivering order'
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'total_price' => 'required',
            'address' => 'required'
        ]);

        $order = Order::create([
            'customer_id' => Auth::id(),
            'total_price' => $request->total_price,
            'address' => $request->address,

        ]);

       // $order->items->attach($id);

        return response()->json([
            'status' => (bool) $order,
            'data'   => $order,
            'message' => $order ? 'Order Created!' : 'Error Creating Order'
        ]);
    }*/

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return response()->json($order, 200);
    }

    /**
     * Update the status of the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {

        //$item = $request->item_id;
        $order = Order::with('customer')->findOrFail($id);

        if($request->get('status')){
             $order->status = $request->get('status');
             if($order->save()){
                $token = $order->customer->token;
                $title = "Your order has been updated";
                $message = "Your order (ORDER #".$order->id.") has been updated to ".$request->get('status');
                
                $tokens = array();
                array_push($tokens, $token);
                
                $data = array(
                    'title' => $title,
                    'message' => $message
                );

                $fields = array(
                    'registration_ids' => $tokens,
                    'notification' => array(
                        "body" => "Your order (ORDER #".$order->id.") has been updated to ".$request->get('status'),
                        'title' => $title
                    )
                );

                $url = 'https://fcm.googleapis.com/fcm/send';
 
						$headers = array(
							'Authorization: key=' . 'AAAAGG2mqGc:APA91bEnltvOsyA7z4KZc-VNUCcCszVJ1_e8lqWLcIRy8rV7bTPWWgiyqbneQK8JUYCHvzMBSrYYW7Na9oXwDdy-iLfZHqyl_BEQnZk69QEpbi17gb1t3G5ADJAJY9gFAwe81j9ytXhi',
							'Content-Type: application/json'
						);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                        $result = curl_exec($ch);
                        
						curl_close($ch);
                return response()->json([
                    'message' => 'Order was updated', 
                    'error' => false,
                    'token' => $token,
                    'fcm_result' => $result
                ]);
             }else{
                return response()->json([
                    'message' => 'Order could not be updated.', 
                    'error' => true
                ]);     
             }
        }else{
            return response()->json([
                'message' => 'Required field missing', 
                'error' => true
            ]);
        }
        
    }


    /**
     * Save orders for a single customer 
     */
    public function saveOrder(Request $request){
        $request->validate([
            'customer_id' => 'required',
            'total_price' => 'required',
            'items' => 'required',
            'address' => 'required',
            'payment_type_id' => 'required',
            'extra_note' => 'required'
        ]);

        $items = json_decode($request->items);

        $order = new Order();
        $order->setConnection('mysql2');

        $order->customer_id = $request->customer_id;
        $order->total_price = $request->total_price;
        $order->address = $request->address;
        $order->payment_type_id = $request->payment_type_id;
        $order->extra_note = $request->extra_note;
        $order->to_be_delivered = $request->to_be_delivered;

        if($order->save()){
           foreach($items as $item){
               $id = $item['item']['id'];
               $quantity = $item['quantity'];
               DB::insert("INSERT INTO item_order(item_id, order_id, quantity) VALUES (?, ?, ?)", [$id, $order->id, $quantity]);

                return response()->json([
                    'error' => false,
                    'message' => 'Order saved'
                ]);
           }
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Could not save the order'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    /**
     * Display the specified resource
     * 
     * Retrieve orders with items made by a specific customer
     */
 
    public function showOrdersByCustomer($id)
    {
        $order = Order::with('items')->where('customer_id', '=', $id)->get();

        return response()->json([
            'data' => $order,
            'message' => $order ? 'Customer retrieved with orders and items' : 'Error retrieving customer'
        ]);
    }

    public function pending_orders()
    {
        $orders = Order::where('status', 'pending')->with('items', 'customer')->latest()->get();

        return response()->json([
            'orders' => $orders,
            'message' => $orders ? 1 : 0
        ]);
    }
}
