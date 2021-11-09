<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $fillable=[
        'total',
        'payment',
        'note',
        'status',
        'fullname',
        'address',
        'phone_number',
        'user_id',
        // 'customer_id',
    ];

    public function orderDetail()
    {
        return $this->hasMany('App\Models\OrderDetail','order_id','id');
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff','staff_id','id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer','customer_id','id');
    }

}
