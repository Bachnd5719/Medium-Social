<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasSlug;

    protected $fillable = [
        "title",
        // "image",
        "content",
        "category_id",
        "slug",
        "user_id",
        "published_at"
    ];

    public function registerMediaConversions(?Media $media = null): void
{
    $this
        ->addMediaConversion('preview')
        ->width(400)
        ->nonQueued();
    $this
        ->addMediaConversion('large')
        ->width(1200)
        ->nonQueued();
}

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function category () {
        return $this->belongsTo(Category::class);
    }

    public function readTime ($wordsPerMinites = 100) {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / $wordsPerMinites);

        return max(1,$minutes);
    }

    public function imageUrl($conversionName = '') {

        $media = $this->getFirstMedia();

        if (!$media) {
            return null;
        }
        if ($media->hasGeneratedConversion($conversionName)) {
            return $media?->getUrl($conversionName);
        }
        return $media?->getUrl();
    }

    public function registerMediaCollections (): void {
        $this->addMediaCollection('default')->singleFile();
    }

    public function emotions()
    {
        return $this->hasMany(Emotion::class);
    }


    public function likesCount(): int
    {
        return $this->emotions()->where('type', 'like')->count();
    }


    public function dislikesCount(): int
    {
        return $this->emotions()->where('type', 'dislike')->count();
    }

    public function currentUserEmotion()
    {
        if (Auth::check()) {
            $user = Auth::user();

            $emotion = $this->emotions()->where('user_id', $user->id)->first();

            if ($emotion) {
                return $emotion->type;
            }

            return 'none';
        }

        return 'none';
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
