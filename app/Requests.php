<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requests extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'contact_number'
    ];
}
