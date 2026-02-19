<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memorial extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'privacy',
        'moderate_memories',
        'allow_comments',
        'views',
        'last_name',
        'first_name',
        'middle_name',
        'birth_date',
        'death_date',
        'birth_place',
        'photo',
        'biography',
        'religion',
        'full_biography',
        'education',
        'education_details',
        'career',
        'career_details',
        'hobbies',
        'character_traits',
        'achievements',
        'military_service',
        'military_rank',
        'military_years',
        'military_conflicts',
        'military_details',
        'military_files',
        'achievement_files',
        'burial_place',
        'burial_city',
        'burial_address',
        'burial_location',
        'burial_latitude',
        'burial_longitude',
        'burial_photos',
        'media_photos',
        'media_videos',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
        'moderate_memories' => 'boolean',
        'allow_comments' => 'boolean',
        'military_conflicts' => 'array',
        'military_files' => 'array',
        'achievement_files' => 'array',
        'burial_photos' => 'array',
        'media_photos' => 'array',
        'media_videos' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function relationships()
    {
        return $this->hasMany(Relationship::class);
    }

    public function relatedUsers()
    {
        return $this->belongsToMany(User::class, 'relationships')
            ->withPivot('relationship_type', 'custom_relationship', 'confirmed', 'visible', 'notes')
            ->withTimestamps();
    }

    public function memories()
    {
        return $this->hasMany(Memory::class)->orderBy('created_at', 'desc');
    }
}
