<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VrGlasses extends Model
{
    protected $table = 'vr_glasses';
    protected $fillable = ['code', 'entry_date', 'life_hours', 'usefull_years', 'deleted_at', 'id_room'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room');
    }
}
