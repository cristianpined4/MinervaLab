<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $table = 'holiday';
    protected $fillable = ['starts_at', 'ends_at', 'description'];
}
