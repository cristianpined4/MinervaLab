<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VrMantenaince extends Model
{
    protected $table = 'vr_mantenaince';
    protected $fillable = ['count', 'status', 'date'];
}
