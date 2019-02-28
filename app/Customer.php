<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

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
}
