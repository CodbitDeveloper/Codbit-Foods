<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $fillable = [
        'suggestion', 'customer_id'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
