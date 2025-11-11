<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservation';
    protected $fillable = [
        'id_user',
        'id_room',
        'date',
        'starts_at',
        'ends_at',
        'time',
        'students',
        'status'
    ];
}
