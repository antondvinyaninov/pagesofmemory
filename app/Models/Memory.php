<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memory extends Model
{
    protected $fillable = [
        'memorial_id',
        'user_id',
        'content',
        'media',
        'likes',
        'views',
    ];

    protected $casts = [
        'media' => 'array',
    ];

    public function memorial()
    {
        return $this->belongsTo(Memorial::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    // Получить связь автора с мемориалом
    public function authorRelationship()
    {
        return Relationship::where('memorial_id', $this->memorial_id)
            ->where('user_id', $this->user_id)
            ->first();
    }
}
