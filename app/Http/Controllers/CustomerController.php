<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Item;
use App\Category;
use App\Order;
use App\PaymentType;
use App\Branch;
use App\Setting;
use App\Restaurant;

use Config;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{

   public function __construct(Request $request)
   {
      if(session('db_name')!=null){
         Config::set('database.connections.mysql2.database', session('db_name')); 
      }else if($request->restaurant != null){
         $restaurant_id = $request->restaurant;
         $restaurant = Restaurant::where('id', $restaurant_id)->first();
         if($restaurant!=null){
            Config::set('database.connections.mysql2.database', $restaurant->DB_name);
         }       
      }
   }
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function store (Request $request)
   {
      $request->validate([
         'name'  => 'required|string',
         'email' => 'required|email|string',
         'phone' => 'required'
      ]);

      $customer = new Customer();

      $customer->name  = $request->name;
      $customer->email = $request->email;
      $customer->phone = $request->phone;

      $customer->save();

      return response()->json([
         'message' => 'Successfully created a customer'
      ]);
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Customer $customer
    *
    * @return \Illuminate\Http\Response
    */
   public function show (Customer $customer)
   {
      return response()->json([
         'data'    => $customer,
         'message' => 'success'
      ], 200);
   }

   /**
    * -----------------------------------------
    * Get all orders for a particular customer
    * -----------------------------------------
    * 
    * @param  \App\Customer $customer
    * @return \Illuminate\Http\Response
    */
   public function showOrders (Customer $customer)
   {
      return response()->json($customer->orders()->with(['item'])->get());
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request $request
    * @param  \App\Customer            $customer
    *
    * @return \Illuminate\Http\Response
    */
   public function update (Request $request, Customer $customer)
   {
      //

   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Customer $customer
    *
    * @return \Illuminate\Http\Response
    */
   public function destroy (Customer $customer)
   {
      //
   }

   /**
    * --------------------------------
    * Fetch all iitems with category
    * --------------------------------
    */
   public function fetchAllItems(Request $request){
      
      if($request->restaurant == null){
         return response()->json([
            'error' => true,
            'message' => 'Unknown restaurant'
         ]);
      }

      $items = Category::with('items', 'items.ingredients')->whereHas('items', function($q){
         $q->where('active', '=', 1);
      })->get();
      
      return response()->json([
         'error' => false,
         'items' => $items
      ]);
   }

   /**
    * --------------------------------------
    * Handle login for a customer mobile app
    * --------------------------------------
    * 
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function handleLogin(Request $request){
      $request->validate([
         'phone' => 'required'
      ]);

      $user = Customer::where('phone', '=', $request->phone)->first();
      
      if($user != null){
         return response()->json([
            'error' => false,
            'newUser' => false,
            'user' => $user
         ]);
      }else{
         $user = new Customer();
         $user->phone = $request->phone;
         $user->firstname = '';
         $user->lastname = '';
         $user->email = '';
         
         if($user->save()){
            return response()->json([
               'error' => false,
               'newUser' => true,
               'id' => $user->id
            ]);
         }else{
            return response()->json([
               'error' => true
            ]);
         }
      }
   }
   
   /**
    * ------------------------------
    * Update profile of a customer
    * ------------------------------
    * 
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function updateProfile(Request $request){
      $request->validate([
         'id' => 'required',
         'firstname' => 'required',
         'lastname' => 'required',
         'email' => 'required'
      ]);

      $customer = Customer::where('id', '=', $request->id)->first();

      $customer->firstname = $request->firstname;
      $customer->lastname = $request->lastname;
      $customer->email = $request->email;

      if($customer->update()){
         return response()->json([
            'error' => false,
            'message' => 'Profile updated'
         ]);
      }else{
         return response()->json([
            'error' => false,
            'message' => 'Could not update profile'
         ]);
      }
   }

   /**
    * -----------------------------------------
    * Get all orders for a particualr customer
    * -----------------------------------------
    * 
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function getOrders(Request $request){
      $request->validate([
         'customer' => 'required'
      ]);

      $orders = Order::with('items')->where('customer_id', '=', $request->customer)->orderBy('created_at', 'desc')->get();

      return response()->json([
         'orders' => $orders
      ]);
   }

   /**
    * ---------------------
    * Save all order
    * ----------------------
    * 
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function saveOrder(Request $request){
      $request->validate([
         'customer' => 'required',
         'to_be_delivered' => 'required',
         'address' => 'required',
         'total' => 'required',
         'payment_type' => 'required',
         'items' => 'required',
         'branch' => 'required'
      ]);

      $items = json_decode($request->items);
      
      $order = new Order;

      $order->customer_id = $request->customer;
      $order->to_be_delivered = $request->to_be_delivered;
      $order->address = $request->address;
      $order->total_price = $request->total;
      $order->payment_type_id = $request->payment_type;
      $order->branch_id = $request->branch;
      
      $payment_type = PaymentType::where('id', '=', $order->payment_type_id)->first();
      
      if($order->save()){
         foreach($items as $item){
            $id = $item->item->id;
            $quantity = $item->quantity;
            DB::connection('mysql2')->insert("INSERT INTO item_order(item_id, order_id, quantity) VALUES (?, ?, ?)", [$id, $order->id, $quantity]);
         }
         return response()->json([
            'error' => false,
            'message' => 'Order saved',
            'order' => $order->id,
            'should_checkout' => $payment_type->name != 'Cash' ? true : false
         ]);
      }else{
         return response()->json([
            'error' => true,
            'message' => 'Could not save the order'
         ]);
      }
   }

   /**
    * ----------------------------------------------
    * Get all payment type options for a restaurant
    * ----------------------------------------------
    * 
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function getOptions(Request $request){
      $payment_types = PaymentType::all();
      $branches = Branch::all();

      return response()->json([
         'error' => false,
         'payment_types' => $payment_types,
         'branches' => $branches,
      ]);
   }

   /**
    * ------------------------------------------------------
    * Initialize payment type for a restaurant using Hubtel
    * ------------------------------------------------------
    * 
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function initializePayment(Request $request){
      $request->validate([
         'order' => 'required'
      ]);

      $order = Order::with('items')->where('id', '=', $request->order)->first();
      $items = array();
      
      foreach($order->items as $single){
         $temp = array(
            "name" => $single->name,
            "quantity" => $single->pivot->quantity,
            "unitPrice" => $single->price
         );
         array_push($items, $temp);
      }
      $url = "https://api.hubtel.com/v2/pos/onlinecheckout/items/initiate";
      $user = 'gPEwQng';
      $key = 'a62e4cda-261f-4bd7-9494-b4f968dad024';
      $basic_auth_key =  'Basic ' . base64_encode($user . ':' . $key);

      $fields = array("items" => $items, "totalAmount" => $order->total_price, "description" => "Codbit Foods Checkout", 
         "callbackUrl" => "http://localhost:8000/api/customer/checkout-response", "returnUrl" => "http://hubtel.com/online", "merchantBusinessLogoUrl" => "http://codbitgh.com/wp-content/uploads/2018/11/CODBIT-LOGO.png", "merchantAccountNumber" => "HM1701190002",
         "cancellationUrl" => "http://hubtel.com/online", "clientReference" => "CBFD-".$order->id.'-'.rand(0, 100));

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 1);

      //Adding post variables to the request
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

      //So that curl_exec returns the contents of the cURL; rather than echoing it
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                  'Authorization: '.$basic_auth_key,
                  'Cache-Control: no-cache',
                  'Content-Type: application/json',
                  ));
      //execute post
      $result = curl_exec($ch);

      //checking if curl returns an error 
      if($result === false){
         echo "cURL error: " . curl_error($ch);
      }

      //close curl and free up the handle
      curl_close($ch);

      //display raw output
      $result = json_decode($result, true);
      if($result['status'] == 'Success'){
         return response()->json([
            'error' => false,
            'data' => $result['data']
         ]);
      }else{
         return response()->json([
            'error' => true,
            'message' => 'Could not initiate checkout. Try again'
         ]);
      }
   }

   /**
    * ----------------
    * Callback handle
    * ----------------
    * 
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function handleCallback(Request $request){
      return response()->json([
         'request' => $request
      ]);
   }

   /**
    * --------------------
    * For handling search
    * --------------------
    * 
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function handleSearch(Request $request){
      $request->validate([
         'term' => 'required'
      ]);

      $term = strtolower(str_replace('%', '\\%', $request->term));

      $items = DB::table('items')->where('name', 'like', '%'.$term.'%')->get();

      return response()->json([
         'error' => false,
         'results' => $items
      ]);
   }

   /**
    * --------------------------
    * Get restaurant details
    * --------------------------
    * 
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function getRestaurantDetails(Request $request)
   {
      $settings = Setting::first();

      return response()->json([
         'error' => false,
         'settings' => $settings
      ]);
   }
}
