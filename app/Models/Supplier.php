<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = "suppliers";
    protected $fillable=[
        'name',
        'address',
        'email',
        'phone_number',
    ];

    public $timestamps = false;

    public function deliveryReceivedNote()
    {
        return $this->hasMany('App\Models\DeliveryReceivedNote','supp_id','id');
    }
}
