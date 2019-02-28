<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use SoftDeletes;

    protected $table = 'restaurants';

    protected $fillable = [
        'name', 'logo', 'email', 'website', 'contact_number', 'DB_name', 'DB_username', 'DB_password'
    ];

}
