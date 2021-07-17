<?php

use Illuminate\Support\Facades\Route;

Route::get('modules', 'ModuleController@index')->name('modules.index');
Route::patch('modules/{module}/disable', 'ModuleController@disable')->name('modules.disable');
Route::patch('modules/{module}/enable', 'ModuleController@enable')->name('modules.enable');
