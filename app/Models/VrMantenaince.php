<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VrMantenaince extends Model
{
    protected $table = 'vr_mantenaince';
    protected $fillable = ['starts_at', 'ends_at', 'description', 'id_vr'];

    public function vrGlasses()
    {
        return $this->belongsTo(VrGlasses::class, 'id_vr');
    }
}
