<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationAttendance extends Model
{
    protected $table = 'reservation_attendance';
    protected $fillable = ['id_reservation', 'carnet', 'date', 'attendance'];
}
