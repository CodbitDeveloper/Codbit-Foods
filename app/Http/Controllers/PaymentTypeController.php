<?php

namespace App\Http\Controllers;

use App\paymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentType = PaymentType::all();

        return response()->json($paymentType, 200);
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
            'name' => 'required|string'
        ]);

        $paymentType = new paymentType();
        $paymentType->setConnection('mysql2');

        if($paymentType->save()){
            return response()->json([
                'data' => $paymentType,
                'message' => 'Successfully created Payment type'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error creating payment type'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\paymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function show(paymentType $paymentType)
    {
        return response()->json($paymentType, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\paymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $paymentType = paymentType::where('id', $request->id)->first();

        return view('paymentTypes', compact('paymentType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\paymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, paymentType $paymentType)
    {
        $status = $paymentType->update(
            $request->only(['name'])
        );

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Payment type Updated Successfully' : 'Error updating payment type'
        ]);
    }

    public function is_active(Request $request)
    {
        $paymentType = paymentType::where('id', $request->id)->first();

        $isactive = $request->get('active');
        $paymentType->active = $isactive;

        if($paymentType->save()){
            return response()->json([
                'data' => $paymentType,
                'message' => 'Payment Type is updated'
            ]);
        }else{
            return response()->json([
                'message' => 'Error updating payment type',
                'error' => true
            ]); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\paymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function destroy(paymentType $paymentType)
    {
        //
    }
}
