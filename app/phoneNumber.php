<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class phoneNumber extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $fillable = [
        'phone_number',
    ];

}
