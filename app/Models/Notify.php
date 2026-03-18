<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notify extends Model
{
    protected $table = 'notify';
    public $timestamps = false;

    protected $fillable = ['id_user', 'title', 'description', 'url', 'date', 'read_at'];

    protected $casts = [
        'date' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
