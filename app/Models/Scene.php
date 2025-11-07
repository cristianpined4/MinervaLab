<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scene extends Model
{
    protected $table = 'scene';
    protected $fillable = ['id_scene_category', 'description', 'duration', 'resource_demo'];

    public function sceneCategory()
    {
        return $this->belongsTo(SceneCategory::class, 'id_scene_category');
    }
}
