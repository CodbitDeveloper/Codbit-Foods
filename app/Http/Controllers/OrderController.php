<?php

namespace App\Http\Controllers;

use App\Order;
use App\Customer;
use App\Category;
use App\Item;
use App\User;
use App\Admin;
use App\Notifications\IncomingOrder;
use App\paymentType;
use App\Dispatch;

use Illuminate\Http\Request;
use Config;
use Auth;

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
        if(strtolower(Auth::user()->role) == 'admin'){
            $orders = Order::with('customer')->latest()->get()->groupBy('status');
            $customers = Customer::all();
            $categories = Category::with('items')->whereHas(
            'items', function($q){
                $q = Item::where('active', 1)->get();
            }
            )->get();
            $payment_types = paymentType::all();
            $dispatches = Dispatch::all();

            return view('orders',compact('orders', 'categories', 'customers', 'payment_types', 'dispatches'));
        }else{
            $orders = Order::with('customer')->where('branch_id', Auth::user()->branch_id)->latest()->get()->groupBy('status');
            $customers = Customer::all();
            $categories = Category::with('items')->whereHas(
            'items', function($q){
            $q = Item::where('active', 1)->get();
            }
            )->get();
            $payment_types = paymentType::all();
            $dispatches = Dispatch::all();

            return view('orders',compact('orders', 'categories', 'customers', 'payment_types', 'dispatches'));
        }
        /*return response()->json([
            'orders' => $orders
        ]);*/
    }

    /**
     * -------------------------------
     * Get orders with order items
     * -------------------------------
     * 
     * @return [Json]
     */
    public function order_with_item()
    {
        $orders = Order::with(['items', 'customer'])->get();
        
        return $orders->toJson();
    }

    /**
     * ------------------
     * Get all orders
     * ------------------
     * 
     * @return [Json]
     */
    public function allOrders()
    {
        $orders = Order::latest()->get();

        return $orders->toJson();
    }
    
     /**
     * --------------------------
     * Get all the latest orders
     * --------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function latestOrders(Request $request)
    {
        $orders = Order::with(['items', 'customer'])->where('id', '>', $request->id)->get();

        return response()->json([
            'data' => $orders,
            'message' => $orders ? 1 : 0
        ]);
    }

    /**
     * ----------------------------------
     * List all orders to be delivered
     * ----------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
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
            'branch_id' => 'required',
            'payment_type_id' => 'required'
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
        $order->payment_type_id = $request->payment_type_id;
        $order->branch_id = $request->branch_id;

        if($order->save()){
            $items = json_decode($request->items, true);

            $order->items()->attach($items);
            $customer = Customer::where('id', $order->customer_id)->first();
            $order->customer = $customer;
            
            $users = User::where('role', 'admin')->orWhere([['role', 'Manager'], ['branch_id', $request->branch_id]])->get();

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
     * --------------------------------------------------------
     * Retrieve orders with items made by a specific customer
     * --------------------------------------------------------
     * 
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function showOrdersByCustomer($id)
    {
        $order = Order::with('items')->where('customer_id', '=', $id)->get();

        return response()->json([
            'data' => $order,
            'message' => $order ? 'Customer retrieved with orders and items' : 'Error retrieving customer'
        ]);
    }


    /**
     * --------------------------------------------------
     * Get all pending orders for a particular restaurant
     * --------------------------------------------------
     * 
     * @return \Illuminate\Http\Response
     */
    public function pending_orders()
    {
        $orders = Order::where('status', 'pending')->with('items', 'customer')->latest()->get();

        return response()->json([
            'orders' => $orders,
            'message' => $orders ? 1 : 0
        ]);
    }

    /**
     * ------------------------------------------
     * Display a page for viewing order details
     * ------------------------------------------
     * 
     * @param  $order
     * @return view
     */
    public function single($order){
        $order = Order::with('items', 'customer')->where('id', '=', $order)->first();
        $dispatches = Dispatch::all();

        return view('order-details', compact('order', 'dispatches'));
    }

    /**
     * -------------------------------------------------------------
     * Present a page for viewing an invoice for a particular order
     * -------------------------------------------------------------
     * 
     * @param  $order
     * @return view
     */
    public function invoice($order){
        $order = Order::with('items', 'customer', 'branch')->where('id', '=', $order)->first();
        return view('invoice', compact('order'));
    }

    /**
     * ----------------------------------------------------------------
     * Generate a weekly report for all orders for a particular restaurant
     * ----------------------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function weeklyReport(Request $request){
        $request->validate([
            'date' => 'required'
        ]);
        $date = substr($request->date, 0, strpos($request->date, " 00:"));
        $date = date('Y-m-d', strtotime($date));
        
        $orders = Order::selectRaw("SUM(total_price) as sales, DAYOFWEEK(created_at) as day")
        ->whereRaw("WEEK(created_at) = WEEK('$date')")->groupBy('day')->get();

        return response()->json(
            [
                'date' => $date,
                'orders' => $orders
            ]
        );
    }

    /**
     * ----------------------------------------------------------------
     * Generate a monthly report for all orders for a particular restaurant
     * ----------------------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function monthlyReport(Request $request){
        $request->validate([
            'date' => 'required'
        ]);

        $date = substr($request->date, 0, strpos($request->date, " 00:"));
        $date = date('Y-m-d', strtotime($date));
        
        $orders = Order::selectRaw("SUM(total_price) as sales, DATE(created_at) as day")
        ->whereRaw("MONTH(created_at) = MONTH('$date')")->groupBy('day')->get();

        return response()->json(
            [
                'date' => $date,
                'orders' => $orders
            ]
        );
    }

    /**
     * --------------------------------------------------
     * Get all orders that have been picked or delivered
     * --------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pickup(Request $request)
    {
        $order = Order::where('id', $request->order_id)->first();

        $order->is_delivered = 1;

        if($order->save()){
            return response()->json([
                'data' => $order,
                'error' => false,
                'message' => 'Order has been picked'
            ]);
        }else{
                return response()->json([
                    'error' => true,
                    'message' => 'Error occured setting the order for pick up'
                ]);
        }
 
    }
}
