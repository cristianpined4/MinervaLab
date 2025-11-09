<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'room';
    protected $fillable = ['description', 'max_students', 'status'];

    public function RoomMantenainces()
    {
        return $this->hasMany(RoomMantenaince::class, 'id_room');
    }
}
