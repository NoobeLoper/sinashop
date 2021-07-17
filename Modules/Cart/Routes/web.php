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

use Illuminate\Support\Facades\Route;

Route::prefix('cart')->group(function() {
    Route::get('/', 'Front\CartController@index')->name('cart.index');
    Route::post('/add/{product}', 'Front\CartController@addToCart')->name('cart.add');
    Route::patch('/quantity/change', 'Front\CartController@quantityChange');
    Route::delete('/delete/{cart}', 'Front\CartController@delete')->name('cart.destroy');
});
