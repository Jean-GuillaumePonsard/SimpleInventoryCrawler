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

// Registering and Login routes
Auth::routes();

// Home page of the website
Route::get('/{home}', 'HomeController@index')->name('home')->where('home', '(home)?');

// Wish list management
// the route /wishlist/product is only available with ajax
Route::post('/wishlist/product', 'WishlistController@addProduct');
Route::delete('/wishlist/product', 'WishlistController@deleteProduct');
Route::get('/wishlist', 'WishlistController@index')->name('wishlist');