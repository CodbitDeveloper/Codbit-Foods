<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;

    protected $guard = 'admin';

    public function getJWTIdentifier() 
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims() 
    {
        return [];
    }

    protected $fillable = [
        'firstname', 'lastname', 'username', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
