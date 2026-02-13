<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'memory_id',
        'user_id',
        'content',
        'likes',
    ];

    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
