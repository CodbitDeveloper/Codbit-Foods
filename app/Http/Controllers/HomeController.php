<?php

namespace App\Http\Controllers;

use DB;
use Config;

use App\Order;
use App\Comment;

use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected $db;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         $this->middleware('auth');
         Config::set('database.connections.mysql2.database', session('db_name'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function setup_dashboard()
    {
        $completed_orders = Order::where('status', 'completed')->whereDate('created_at', Carbon::today())->get()->count();

        $pending_orders = Order::where('status', 'pending')->whereDate('created_at', Carbon::today())->get()->count();

        $inProgress_orders = Order::where('status', 'in-progress')->whereDate('created_at', Carbon::today())->get()->count();

        $comments = Comment::whereDate('created_at', Carbon::today())->get()->count();

        $total_price = Order::whereDate('created_at', Carbon::today())->sum('total_price');

        return response()->json([
            'completed_orders' => $completed_orders,
            'pending_orders' => $pending_orders,
            'inProgress_orders' => $inProgress_orders,
            'comments' => $comments,
            'total_price' => $total_price,
        ]);

    }

    public function week_sales()
    {
        $orders = Order::selectRaw('SUM(total_price) as sales, DAYOFWEEK(created_at) as day')
        ->whereRaw('WEEK(created_at) = WEEK(CURDATE())')->groupBy('day')->get();

        return response()->json(
            [
                'orders' => $orders
            ]
        );
    }
}
