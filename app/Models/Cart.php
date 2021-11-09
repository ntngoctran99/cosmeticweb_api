<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = "carts";
    public $timestamps = false;
    protected $fillable=[
        'customer_id',
        'product_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id','product_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer','customer_id','customer_id');
    }
}
