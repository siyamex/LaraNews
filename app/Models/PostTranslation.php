<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostTranslation extends Model
{
    protected $fillable = [
        'post_id', 'locale', 'title', 'slug', 'excerpt', 'content',
        'meta_title', 'meta_description', 'og_title', 'og_description',
        'og_image', 'canonical_url', 'faq',
    ];

    protected $casts = [
        'faq' => 'array',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function getEffectiveMetaTitleAttribute(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function getEffectiveMetaDescriptionAttribute(): string
    {
        return $this->meta_description ?: strip_tags($this->excerpt ?? '');
    }

    public function getEffectiveOgTitleAttribute(): string
    {
        return $this->og_title ?: $this->meta_title ?: $this->title;
    }

    public function isRtl(): bool
    {
        return in_array($this->locale, ['dv', 'ar', 'fa', 'he', 'ur']);
    }
}
