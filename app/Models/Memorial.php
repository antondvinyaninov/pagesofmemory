<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memorial extends Model
{
    protected $fillable = [
        'user_id',
        'status',
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
        'necrologue',
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
        'military_details',
        'burial_place',
        'burial_city',
        'burial_address',
        'burial_location',
        'burial_latitude',
        'burial_longitude',
        'burial_photos',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
        'burial_photos' => 'array',
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
