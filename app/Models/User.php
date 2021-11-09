<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
//use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable
{
//class User extends Model implements Authenticatable, AuthorizableContract
//{
    use HasFactory, Notifiable,HasApiTokens;
    protected $table = "users";

    public function review()
    {
        return $this->hasMany('App\Models\Review','user_id','id');
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'fullname',
        'phone_number',
        'address',
        'email',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
