<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeProduct extends Model
{
    use HasFactory;
    protected $table = 'type_products';
    protected $fillable=[
        'name',
        'description',
        'image',
    ];

    public function product(){
        return $this->hasMany('App\Models\Product','type_id','id');
    }
}
