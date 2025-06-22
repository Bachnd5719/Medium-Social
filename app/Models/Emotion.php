<?php

namespace App\Models;

use App\Enums\EmotionType;
use Illuminate\Database\Eloquent\Model;

class Emotion extends Model
{
    public const UPDATED_AT = null;
    protected $fillable = [
        'user_id',
        'post_id',
        'type',
    ];

    protected $casts = [
    'type' => EmotionType::class,
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
