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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Admin Routes
Route::group( ['middleware' => 'auth' ], function() {
Route::get('/all-items', 'ItemContorller@index');
Route::get('/all-items/create', 'ItemContorller@create');
Route::post('/getItemData', 'ItemContorller@getItemData');
Route::post('/all-items/store', 'ItemContorller@store')->name('itemStore');
Route::post('/all-items/update', 'ItemContorller@update')->name('itemUpdate');
Route::get('/all-items/{id}/edit', 'ItemContorller@edit');
Route::get('/all-items/delete/{id}', 'ItemContorller@destroy');
});