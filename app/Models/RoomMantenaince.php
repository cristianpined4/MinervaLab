<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomMantenaince extends Model
{
    protected $table = 'room_mantenaince';
    protected $fillable = ['starts_at', 'ends_at', 'id_room', 'description'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room');
    }
}
