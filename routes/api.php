<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('admin/add', 'AdminController@store')->name('admin.add');
Route::post('admin/login', 'Auth\AdminLoginController@login')->name('admin.login');
Route::post('restaurant/add', 'RestaurantController@store')->name('restaurant.add');
Route::post('restaurant/add_db', 'RestaurantController@create_db')->name('restaurant.add_db');
Route::post('user/add', 'UserController@store')->name('user.add');

Route::get('/init-dashboard-cards', 'HomeController@setup_dashboard')->name('init-dashboard-cards');

Route::get('/orders/get-pending', 'OrderController@pending_orders')->name('orders.pending');
Route::get('/orders/get-weekly-orders', 'HomeController@week_sales')->name('orders.weekly');

Route::get('/categories/cummulated', 'CategoryController@getCategoryItems')->name('categories.cummulated');
Route::post('/categories/add', 'CategoryController@store')->name('categories.add');