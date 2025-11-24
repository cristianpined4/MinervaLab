<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAttendance extends Model
{
    protected $table = 'admin_attendance';
    protected $fillable = ['key'];
}
