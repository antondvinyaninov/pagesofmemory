<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country',
        'region',
        'city',
        'avatar',
        'profile_type',
        'show_email',
        'show_memorials',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function memorials()
    {
        return $this->hasMany(Memorial::class);
    }

    public function relationships()
    {
        return $this->hasMany(Relationship::class);
    }

    public function relatedMemorials()
    {
        return $this->belongsToMany(Memorial::class, 'relationships')
            ->withPivot('relationship_type', 'custom_relationship', 'confirmed', 'visible', 'notes')
            ->withTimestamps();
    }
}
