<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table='customers';
    protected $fillable=[
        'fullname',
        'phone_number',
        'gender',
        'birthday',
        'address',
        'email',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function order()
    {
        return $this->hasMany('App\Models\Order','customer_id','id');
    }

    public function cart()
    {
        return $this->hasMany('App\Models\Cart','customer_id','id');
    }

    public function review()
    {
        return $this->hasMany('App\Models\Review','customer_id','id');
    }
}
