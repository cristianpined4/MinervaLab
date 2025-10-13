<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomMantenaince extends Model
{
    protected $table = 'room_mantenaince';
    protected $fillable = ['id_room', 'status', 'date'];
}
