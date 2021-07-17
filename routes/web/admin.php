<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('admin.main');
// });

// Demo of AdminPanelLte
Route::get('/demo', function () {
    return view('admin.demo');
});
// ./Demo of AdminPanelLte

Route::get('/', function () {
    return view('admin.index');
});

Route::resource('users', 'UserController');
Route::get('/users/{user}/permissions', 'PermissionForUserController@create')->name('users.permissions.create')->middleware('can:staff-user-permissions');
Route::post('/users/{user}/permissions', 'PermissionForUserController@store')->name('users.permissions.store')->middleware('can:staff-user-permissions');
Route::resource('permissions', 'PermissionController');
Route::resource('roles', 'RoleController')->except(['show']);
Route::resource('products', 'ProductController');
Route::post('attribute/values' , 'AttributeController@getValues')->name('attribute.values');

Route::post('comments/approving/{comment}', 'CommentController@approving')->middleware('can:show-approved-comments')->name('comments.approving');
Route::resource('comments', 'CommentController')->only(['index', 'edit', 'update', 'destroy']);
Route::resource('categories', 'CategoryController');
Route::resource('orders', 'OrderController');
Route::get('orders/{order}/orders', 'OrderController@payment')->name('orders.payments');
Route::resource('products.gallery', 'ProductGalleryController');

