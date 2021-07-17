<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['price', 'tracking_serial', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function products()
   {
       return $this->belongsToMany(Product::class)->withPivot(['quantity']);
   }

   public function payments()
   {
       return $this->hasMany(Payment::class);
   }
}
