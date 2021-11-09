<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = "images";
    
    protected $fillable = [
        'product_id',
        'image',
        'type_image',
        'del_flag'
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id','product_id');
    }

}
