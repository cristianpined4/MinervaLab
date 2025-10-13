<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationDetail extends Model
{
    protected $table = 'reservation_details';
    protected $fillable = ['id_reservation', 'id_scene', 'count_sessions'];
}
