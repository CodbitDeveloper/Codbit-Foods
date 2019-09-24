<?php

namespace App\Http\Controllers;

use DB;
use Config;
use Auth;

use App\Order;
use App\Comment;
use App\Category;

use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         //config(['auth.defaults.guard' => 'web']);
         $this->middleware(['auth', 'web']);
         Config::set('database.connections.mysql2.database', session('db_name'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(strtolower(Auth::user()->role) == 'admin'){
            $pending_orders = Order::with('customer')->where('status', 'pending')->get();
        }else{
            $pending_orders = Order::with('customer')->where([['status', 'pending'], ['branch_id', Auth::user()->branch_id]])->get();
        }
        $categories = Category::with('items')->get();
        return view('home', compact('pending_orders', 'categories'));
    }

    /**
     * --------------------------------------
     * Setting up dashboard for a restaurant
     * --------------------------------------
     * 
     * @return \Illuminate\Http\Response
     */
    public function setup_dashboard()
    {
        if(strtolower(Auth::user()->role) == 'admin'){
            $completed_orders = Order::where('status', 'completed')->whereDate('created_at', Carbon::today())->get()->count();

            $inProgress_orders = Order::where('status', 'in-progress')->whereDate('created_at', Carbon::today())->get()->count();

            $comments = Comment::whereDate('created_at', Carbon::today())->get()->count();

            $total_price = Order::whereDate('created_at', Carbon::today())->sum('total_price');
        }else{
            $completed_orders = Order::where([['status', 'completed'], ['branch_id', Auth::user()->branch_id]])->whereDate('created_at', Carbon::today())->get()->count();

            $inProgress_orders = Order::where([['status', 'in-progress'], ['branch_id', Auth::user()->branch_id]])->whereDate('created_at', Carbon::today())->get()->count();
    
            $comments = Comment::where('branch_id', Auth::user()->branch_id)->whereDate('created_at', Carbon::today())->get()->count();
    
            $total_price = Order::where('branch_id', Auth::user()->branch_id)->whereDate('created_at', Carbon::today())->sum('total_price');
    
        }
        return response()->json([
            'completed' => $completed_orders,
            'in_progress' => $inProgress_orders,
            'comments' => $comments,
            'total_price' => $total_price,
        ]);

    }

    /**
     * -----------------------------------------------------
     * Display the weekly sales for a particular restaurant
     * -----------------------------------------------------
     * 
     * @return \Illuminate\Http\Response
     */
    public function week_sales()
    {
        if(\strtolower(Auth::user()->role) == 'admin'){
            $orders = Order::selectRaw('SUM(total_price) as sales, DAYOFWEEK(created_at) as day')
            ->whereRaw('WEEK(created_at) = WEEK(CURDATE())')->groupBy('day')->get();
        }else{
            $orders = Order::where('branch_id', Auth::user()->branch_id)->selectRaw('SUM(total_price) as sales, DAYOFWEEK(created_at) as day')
            ->whereRaw('WEEK(created_at) = WEEK(CURDATE())')->groupBy('day')->get();
        }
        return response()->json(
            [
                'orders' => $orders
            ]
        );
    }
}
