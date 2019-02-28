<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $connection = 'mysql2';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'username', 'password', 'phone', 'gender', 'branch_id', 'active', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //Relationship : one to many
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function promos()
    {
        return $this->hasMany('App\Promo');
    }
}
