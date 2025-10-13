<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservation';
    protected $fillable = [
        'id_user',
        'id_calendar',
        'id_room',
        'day',
        'hour',
        'time',
        'count',
        'status',
        'date'
    ];
}
