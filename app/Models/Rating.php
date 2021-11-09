<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $table = "ratings";
    protected $fillable = ['user_id', 'product_id', 'rating'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id','product_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer','customer_id','customer_id');
    }
    
}
