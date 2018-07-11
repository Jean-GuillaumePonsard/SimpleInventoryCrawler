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

Route::get('/', function (\App\Management\DishwasherManagement $dishwasherManagement) {
    return view('home', ['productsList' => $dishwasherManagement->load()]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
