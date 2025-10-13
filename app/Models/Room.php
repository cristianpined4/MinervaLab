<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'room';
    protected $fillable = ['description', 'vr_glasses', 'max_students', 'status'];
}
