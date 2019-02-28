<?php

namespace App\Http\Middleware;
use Config;
use Closure;
use DB;
class ChangeDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(session('db_name') != null){
            Config::set('database.connections.mysql2.database', session('db_name'));
            DB::purge();
        }
        
        return $next($request);
    }
}
