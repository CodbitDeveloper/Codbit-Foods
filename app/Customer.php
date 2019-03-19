<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Authenticatable
{
    use SoftDeletes, Notifiable;

    protected $connection = 'mysql2';

    protected $fillable = [
        'firstname', 'lastname', 'email', 'phone', 'password'
    ];

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function feedbacks()
    {
        return $this->hasMany('App\Feedback');
    }

    public function getFullNameAttribute()
    {
        return $this->firstname." ".$this->lastname; 
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function promos()
    {
        return $this->belongsToMany('App\Promo', 'customer_promo')->withTimestamps();
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
    
}
