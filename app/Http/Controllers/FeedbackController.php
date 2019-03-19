<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\Customer;
use App\User;
use App\Notifications\Feedbacks;

use Config;
use Auth;

use Illuminate\Http\Request;

class FeedbackController extends Controller
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
            $feedbacks = Feedback::with('customer', 'responses')->latest()->get()->groupBy('customer_id');
        }else if(strtolower(Auth::user()->role) == 'manager'){
            $feedbacks = Feedback::with('customer', 'responses')->where('branch_id', Auth::user()->branch_id)->latest()->get()->groupBy('customer_id');
        }else{
            return abort(403);
        }
        
        return view('feedback', compact('feedbacks'));
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
            'suggestion' => 'required',
            'customer_id' => 'required',
            'branch_id' => 'required',
            'ratings' => 'required'
        ]);

        $feedback = new Feedback();

        $feedback->suggestion = $request->suggestion;
        $feedback->customer_id = $request->customer_id;
        $feedback->branch_id = $request->branch_id;
        $feedback->ratings = $request->ratings;

        if($feedback->save()){
            $users = User::where([['role', 'admin'], ['branch_id', $request->branch_id]])->orWhere('role', 'Manager')->get();

            foreach($users as $user){
                $user->notify(new Feedbacks($feedback));
            }

            return response()->json([
                'data' => $feedback,
                'message' => 'Feedback sent successfully',
                'error' => false
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error saving feedback, Try Again!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback $feedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function edit(Feedback $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feedback $feedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedback $feedback)
    {
        //
    }
}
