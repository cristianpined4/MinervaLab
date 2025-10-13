<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'age',
        'email',
        'phone',
        'password',
        'user_rol',
        'admin'
    ];
}
