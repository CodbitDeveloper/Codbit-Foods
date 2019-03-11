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
Route::get('/menu', 'ItemController@index')->name('items');
Route::get('/categories', 'CategoryController@index')->name('categories');
Route::get('/item/{item}', 'ItemController@viewItem')->name('item.details');
Route::get('/orders', 'OrderController@index')->name('orders');
Route::get('/order/{order}', 'OrderController@single')->name('order.details');
Route::get('/employees', 'UserController@index')->name('employees');
Route::get('/feedback', 'FeedbackController@index')->name('feedback');
Route::get('/branches', 'BranchController@index')->name('branches');