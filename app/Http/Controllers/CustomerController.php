<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
   /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function index ()
      {
         //return response()->json(Customer::with(['orders'])->get());
         $customers = Customer::all();

         return response()->json([
            'data' => $customers,
            'message' =>'success'
            ], 200);
      }

      
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = new Customer();

        return view('customers.create', compact('customer'));
    }

      /**
       * Store a newly created resource in storage.
       *
       * @param  \Illuminate\Http\Request $request
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
         $customer->setConnection('mysql2');

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
       * Check if the access code is valid
       */
      public function checkCode (Request $request)
      {
         $request->validate([
            'phone' => 'required|string',
            'code'  => 'required|string'
         ]);

         $customer = Customer::where('phone', '=', $request->phone)->first();
         //var_dump($customer);
         if ($customer != null)
         {
            if ($customer->password == $request->code)
            {
               return response()->json([
                  'error'   => false,
                  'message' => "User verification successful"
               ]);
            }
            else
            {
               return response()->json([
                  'error'   => true,
                  'message' => "Wrong password entered"
               ]);
            }
         }
         else
         {
            return response()->json([
               'error'   => true,
               'message' => "No user with specified phone number"
            ]);
         }

      }

      /**
       * Login with facebook
       */
      public function facebookLogin (Request $request)
      {
         $request->validate([
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => 'required|string',
            'phone'      => 'required|string'
         ]);

         if ($request->phone == "")
         {
            $request->phone = null;
         }

         $fname = $request->first_name;
         $lname = $request->last_name;
         $email = $request->email;
         $phone = $request->phone;

         DB::insert("INSERT IGNORE INTO customers(firstname, lastname, email, phone) VALUES (?, ?, ?, ?)", [$fname, $lname, $email, $phone]);

         $customer = Customer::where('email', '=', $request->email)->first();

         if ($customer != null)
         {
            return response()->json([
               "error" => true,
               "user"  => $customer
            ]);
         }
         else
         {
            return response()->json([
               "error"   => true,
               "message" => "No user with specified email"
            ]);
         }
      }

      /**
       * Login with phone number
       */
      public function phoneLogin (Request $request)
      {
         $request->validate([
            'phone' => 'required|string'
         ]);

         $customer = Customer::where('phone', '=', $request->phone)->first();

         if ($customer != null)
         {
            $digits             = 4;
            $random_code        = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
            $customer->password = $random_code;

            if ($customer->update())
            {
               //SMS logic comes in here
               return response()->json([
                  "error"   => false,
                  "message" => "Verification code has been sent "
               ]);
            }
            else
            {
               return response()->json([
                  "error"   => true,
                  "message" => "Could not save code"
               ]);
            }
         }
         else
         {
            return response()->json([
               "error"   => true,
               "message" => "No user with specified phone number"
            ]);
         }
      }

      /**
       * Save the user FCM token
       */
      public function saveFCM (Request $request)
      {
         $request->validate([
            'phone' => 'required|string',
            'token' => 'required|string'
         ]);

         $customer = Customer::where('phone', '=', $request->phone)->first();

         $customer->token = $request->token;
         if ($customer->update())
         {
            return response()->json([
               "error"   => false,
               "message" => "FCM token saved"
            ]);
         }
         else
         {
            return response()->json([
               "error"   => true,
               "message" => "Could not save token"
            ]);
         }
      }


      /**
       *Save a new user with details
       */
      public function saveUser (Request $request)
      {
         $request->validate([
            'firstname' => 'required|string',
            'lastname'  => 'required|string',
            'email'     => 'required|string',
            'phone'     => 'required|string'
         ]);

         if (count(Customer::where("phone", "=", $request->phone)->first()) > 0)
         {
            return response()->json([
               "error"   => true,
               "message" => "Phone number has already been used for a previous registration."
            ]);
         }
         else if (count(Customer::where("email", "=", $request->email)->first()) > 0)
         {
            return response()->json([
               "error"   => true,
               "message" => "Email has already been used for a previous registration."
            ]);
         }
         else
         {
            $customer            = new Customer();
            $customer->firstname = $request->firstname;
            $customer->lastname  = $request->lastname;
            $customer->email     = $request->email;
            $customer->phone     = $request->phone;

            if ($customer->save())
            {
               return response()->json([
                  "error"   => false,
                  "message" => "User saved."
               ]);
            }
            else
            {
               return response()->json([
                  "error"   => true,
                  "message" => "Could not save user."
               ]);
            }
         }
      }

}
