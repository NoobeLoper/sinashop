<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::get('/','indexController@index');


// Route::get('/', function () {
//     // alert()->info('به سینا شاپ خوش آمدید', 'خوش آمدید')->autoclose('2000');
//     //! For Dev, Delete Line 22 Later:
    Auth::loginUsingId(8);
//     return view('index');
// });

//For Download Files With Secure Links
// Route::get('download/{file}', function ($file) {
//    return Storage::download('files2/' $file /!OR!/  $request->path());
// })->name('download.file')->middleware('signed');

// Route::get('/', function () {
//     return \Illuminate\Support\Facades\URL::temporarySignedRoute('download.file' , now()->addSeconds(30) , ['user' => auth()->user()->id , 'path' => '/files/1.jpg']);
// });

Auth::routes(['verify' => true]);


Route::get('/auth/google', 'Auth\GoogleAuthController@redirect')->name('auth.google');
Route::get('/auth/google/callback', 'Auth\GoogleAuthController@callback');

Route::get('/auth/token' ,'Auth\AuthTokenController@getToken')->name('2fa.token');
Route::post('/auth/token' ,'Auth\AuthTokenController@postToken');


Route::get('/home', 'HomeController@index')->middleware('auth', 'verified')->name('home');

Route::get('/secret', function(){
    return 'secret page!';
})->middleware('auth', 'password.confirm');

Route::prefix('/profile')->middleware('auth')->group(function() {
    Route::get('/', 'ProfileController@index')->name('profile');
    Route::get('/twofactor', 'ProfileController@manageTwoFactor')->name('profile.two.factor');
    Route::post('/twofactor', 'ProfileController@postManageTwoFactor');
    Route::get('/twofactor/phone', 'ProfileController@getPhoneVerify')->name('profile.two.factor.phone');
    Route::post('/twofactor/phone', 'ProfileController@postPhoneVerify');

    Route::get('orders', 'OrderController@index')->name('profile.orders');
    Route::get('orders/{order}', 'OrderController@show')->name('profile.order.show');
    Route::get('orders/{order}/payment', 'OrderController@payment')->name('profile.order.payment');
});

Route::get('/products', 'ProductController@index');
Route::get('products/{product}', 'ProductController@show');
Route::post('comments', 'HomeController@comment')->middleware('auth')->name('send.comment');

Route::get('/categories', 'CategoryController@index');
Route::get('categories/{category}', 'CategoryController@show');

// Cart & Payment & Orders

Route::post('payment', 'PaymentController@payment')->middleware('auth')->name('cart.payment');
Route::get('payment/callback', 'PaymentController@callback')->middleware('auth')->name('payment.callback');
