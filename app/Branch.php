<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';
    
    protected $fillable = [
        'name', 'location', 
    ];


    //Relationship : one to many(reversed)
    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
