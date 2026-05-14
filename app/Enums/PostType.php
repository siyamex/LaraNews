<?php

namespace App\Enums;

enum PostType: string
{
    case Article  = 'article';
    case Gallery  = 'gallery';
    case Video    = 'video';
    case Audio    = 'audio';
    case LiveBlog = 'liveblog';
    case Podcast  = 'podcast';

    public function label(): string
    {
        return match($this) {
            self::Article  => 'Article',
            self::Gallery  => 'Photo Gallery',
            self::Video    => 'Video',
            self::Audio    => 'Audio',
            self::LiveBlog => 'Live Blog',
            self::Podcast  => 'Podcast',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Article  => 'document-text',
            self::Gallery  => 'photograph',
            self::Video    => 'film',
            self::Audio    => 'volume-up',
            self::LiveBlog => 'lightning-bolt',
            self::Podcast  => 'microphone',
        };
    }

    public function hasVideo(): bool
    {
        return in_array($this, [self::Video, self::LiveBlog]);
    }

    public function hasAudio(): bool
    {
        return in_array($this, [self::Audio, self::Podcast]);
    }

    public function hasGallery(): bool
    {
        return $this === self::Gallery;
    }
}
