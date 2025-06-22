<?php

namespace App\Enums;

enum EmotionType: string
{
    case Like = 'like';
    case Dislike = 'dislike';
    case None = 'none';

    public function label(): string
    {
        return match ($this) {
            self::Like => 'Like',
            self::Dislike => 'Dislike',
            self::None => 'None',
        };
    }
}