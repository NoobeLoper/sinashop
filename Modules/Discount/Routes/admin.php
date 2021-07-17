<?php

use Illuminate\Support\Facades\Route;

Route::resource('discounts', 'DiscountController')->except(['show']);
