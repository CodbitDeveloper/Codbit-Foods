<?php

namespace App\Http\Controllers;

use App\Order;
use App\Customer;
use App\Category;
use App\Item;
use App\User;
use App\Admin;
use App\Notifications\IncomingOrder;

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
        $orders = Order::with('customer')->latest()->get()->groupBy('status');
        $customers = Customer::all();
        $categories = Category::with('items')->whereHas(
            'items', function($q){
                $q = Item::where('active', 1)->get();
            }
        )->get();
        return view('orders',compact('orders', 'categories', 'customers'));
        /*return response()->json([
            'orders' => $orders
        ]);*/
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
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required',
            'total' => 'required',
            'branch_id' => 'required'
        ]);

        if($request->customer_id == null){
            $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'phone' => 'required',
                'email' => 'required' 
           ]);
           
           $customer = new Customer();
           $customer->firstname = $request->firstname;
           $customer->lastname = $request->lastname;
           $customer->email = $request->email;
           $customer->phone = $request->phone;

           $customer->save();

           $customer_id = $customer->id;
        }else{
            $customer_id  = $request->customer_id;
        }

        $order = new Order();
        $order->customer_id = $customer_id;
        $order->address = "In shop purchase";
        $order->total_price = $request->total;
        $order->to_be_delivered = false;
        $order->status = "In-Progress";
        $order->has_paid = 1;
        $order->payment_type_id = 4;
        $order->branch_id = $request->branch_id;

        if($order->save()){
            $items = json_decode($request->items, true);

            $order->items()->attach($items);

            $users = User::where([['role', 'admin'], ['branch_id', $request->branch_id]])->orWhere('role', 'Manager')->get();

            foreach($users as $user){
                $user->notify(new IncomingOrder($order));
            }

            $admins = Admin::all();

            foreach($admins as $admin){
                $admin->notify(new IncomingOrder($order));
            }

            return response()->json([
                'error' => false,
                'message' => 'Order saved.',
                'data' => $order
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Could not save the order'
            ]);
        }
    }

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
    public function updateStatus(Request $request)
    {

        $request->validate([
            'order_id'=>'required',
            'status' => 'required'
        ]);

        $order = Order::where('id', '=', $request->order_id)->first();

        $order->status = $request->get('status');
        if($order->update()){
            return response()->json([
                'message' => 'Order was updated', 
                'error' => false
            ]);
         }else{
            return response()->json([
                'message' => 'Order could not be updated.', 
                'error' => true
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

    public function single($order){
        $order = Order::with('items', 'customer')->where('id', '=', $order)->first();
        return view('order-details', compact('order'));
    }
}
