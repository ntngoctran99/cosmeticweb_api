<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";

    protected $fillable=[

        'name',
        'description',
        'unit_price',
        'promotion_price',
        'unit',
        'views',
        'type_id',
        'best_seller',
        'latest',
        'top_rated',
        'sample_home',
        'suppliers_id',
        'del_flag',
    ];
    public function producttype()
    {
        return $this->belongsTo('App\Models\TypeProduct','type_id','id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier','suppliers_id','id');
    }

    public function orderDetail()
    {
        return $this->hasMany('App\Models\OrderDetail','product_id','id');
    }

    public function review()
    {
        return $this->hasMany('App\Models\Review','product_id','id');
    }

    public function cart()
    {
        return $this->hasMany('App\Models\Cart','product_id','id');
    }

    public function detailDeliveryReceivedNote()
    {
        return $this->hasMany('App\Models\DetailDeliveryReceivedNote.php','product_id','id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProductImage','product_id','id');
    }

}
