<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    protected $fillable = [
        'memorial_id',
        'user_id', 
        'relationship_type',
        'custom_relationship',
        'confirmed',
        'visible',
        'notes'
    ];

    protected $casts = [
        'confirmed' => 'boolean',
        'visible' => 'boolean',
    ];

    public function memorial()
    {
        return $this->belongsTo(Memorial::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
