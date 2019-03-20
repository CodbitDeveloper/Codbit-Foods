<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['middleware' => 'change.database'], function(){
    Auth::routes();

    Route::get('/', function () {
        return view('welcome');
    });
});


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/categories', 'CategoryController@index')->name('categories');
Route::post('/users/logout', 'Auth\LoginController@userLogout')->name('user.logout');

Route::middleware('admin')->prefix('admin')->group(function(){
    Route::post('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
    Route::get('/', 'RestaurantController@index')->name('admin.dashboard');
    Route::get('/restaurants/add', 'RestaurantController@create')->name('admin.restaurants.add');
});

Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');

Route::get('/menu', 'ItemController@index')->name('items');
Route::get('/categories', 'CategoryController@index')->name('categories');
Route::get('/item/{item}', 'ItemController@viewItem')->name('item.details');
Route::get('/orders', 'OrderController@index')->name('orders');
Route::get('/order/{order}', 'OrderController@single')->name('order.details');
Route::get('/employees', 'UserController@index')->name('employees');
Route::get('/feedback', 'FeedbackController@index')->name('feedback');
Route::get('/branches', 'BranchController@index')->name('branches');
Route::get('/search', 'SettingController@search')->name('search');
Route::get('/invoice/{order}', 'OrderController@invoice')->name('invoice');
Route::get('/reports', 'SettingController@reports')->name('reports');
Route::get('/deals', 'DealController@index')->name('deals.promotions');
