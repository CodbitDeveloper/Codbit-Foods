<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispatch extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $fillable = [
        'firstname', 'lastname', 'phone', 'active'
    ];

    public function deliveries(){
        return $this->hasMany('App\Delivery');
    }
}
