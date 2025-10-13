<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    protected $table = 'notify';
    protected $fillable = ['id_user', 'title', 'description', 'url', 'date'];
}
