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

Route::get('/init-dashboard-cards', 'HomeController@setup_dashboard')->name('init-dashboard-cards');

Route::get('/orders/get-pending', 'OrderController@pending_orders')->name('orders.pending')->middleware('jwt.auth');
Route::get('/orders/get-weekly-orders', 'HomeController@week_sales')->name('orders.weekly');

Route::get('/categories/cummulated', 'CategoryController@getCategoryItems')->name('categories.cummulated');
Route::post('/categories/add', 'CategoryController@store')->name('categories.add');
Route::put('/categories/update/{category}', 'CategoryController@update')->name('categories.update');
Route::post('/categories/update/{category}', 'CategoryController@update')->name('categories.update');
Route::delete('/categories/delete/{category}', 'CategoryController@destroy')->name('categories.delete');

Route::put('/items/activate/{item}', 'ItemController@toggleActive')->name('items.activate');
Route::post('/items/add', 'ItemController@store')->name('items.add');
Route::post('/items/update/{item}', 'ItemController@update')->name('items.update');

Route::post('/orders/add', 'OrderController@store')->name('orders.add');
Route::put('/order/update-status', 'OrderController@updateStatus')->name('order.update-status');


Route::post('/user/add', 'UserController@store')->name('user.add');
Route::put('/user/edit', 'UserController@update')->name('user.edit');
Route::post('/user/activate', 'UserController@is_active')->name('user.activate');

Route::post('/branches/add', 'BranchController@store')->name('branch.add');
Route::post('/branch/activate', 'BranchController@is_active')->name('branch.activate');

Route::prefix('customer')->middleware('cors')->group(function(){
    Route::get('/items', 'CustomerController@fetchAllItems');
    Route::post('/login', 'CustomerController@handleLogin');
    Route::post('/update', 'CustomerController@updateProfile');
    Route::get('/orders', 'CustomerController@getOrders');
    Route::get('/order-options', 'CustomerController@getOptions');
    Route::post('/order', 'CustomerController@saveOrder');
    Route::post('/checkout', 'CustomerController@initializePayment');
    Route::post('/checkout-response', 'CustomerController@handleCallback');
    Route::get('/search', 'CustomerController@handleSearch');
    Route::get('/restaurant', 'CustomerController@getRestaurantDetails');
    Route::get('/change-conn', 'CustomerController@changeConnection');
    Route::get('/test-conn', 'CustomerController@testConnection');
});

Route::post('/report/order/weekly', 'OrderController@weeklyReport')->name('report.sales.weekly');
Route::post('/report/order/monthly', 'OrderController@weeklyReport')->name('report.sales.weekly');

Route::post('/deals/add', 'DealController@store')->name('deals.add');
Route::delete('/deals/delete/{deal}', 'DealController@destroy')->name('deals.delete');
Route::post('/promo/add', 'PromoController@store')->name('promo.add');
Route::post('/promo/activate', 'PromoController@is_active')->name('promo.activate');
Route::delete('/promo/delete/{promo}', 'PromoController@destroy')->name('promo.delete');

Route::post('/dispatch/add', 'DispatchController@store')->name('dispatch.add');
Route::put('/dispatch/edit', 'DispatchController@update')->name('dispatch.edit');
Route::post('/dispatch/activate', 'DispatchController@is_active')->name('dispatch.activate');
