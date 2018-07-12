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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/wishlist/product', 'WishlistController@addProduct');
Route::post('/wishlist/products');
Route::delete('/wishlist/product', 'WishlistController@deleteProduct');
Route::delete('/wishlist/products');
Route::get('/wishlist', 'WishlistController@index')->name('wishlist');
// There is no other action that we want the user to do