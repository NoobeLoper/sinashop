<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsedDiscount extends Model
{
    protected $fillable = ['user_id', 'discount_code'];
}
