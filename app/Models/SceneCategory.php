<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SceneCategory extends Model
{
    protected $table = 'scene_category';
    protected $fillable = ['description', 'color'];
}
