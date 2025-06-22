<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, InteractsWithMedia, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'image',
        'bio',
        'password',
    ];

    public function following() {
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }

    public function followers() {
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }

    public function isFollowedBy(User $user) {
        return $this->followers()->where('follower_id',$user->id)->exists();
    }

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

    public function imageUrl() {
        $media = $this->getFirstMedia('avatar');

        if (!$media) {
            return null;
        }
        if ($media->hasGeneratedConversion('avatar')) {
            return $media?->getUrl('avatar');
        }
        return $media?->getUrl();
    }

    public function posts () {
        return $this->hasMany(Post::class);
    }

    public function emotions () {
        return $this->hasMany(Emotion::class);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
    $this
        ->addMediaConversion('avatar')
        ->width(100)
        ->crop(100,100)
        ->nonQueued();
    }

    public function registerMediaCollections (): void {
        $this->addMediaCollection('avatar')->singleFile();
    }
}