<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'type', 'is_featured', 'color', 'meta'];

    protected $casts = [
        'is_featured' => 'boolean',
        'meta' => 'array',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(TagTranslation::class);
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tag')->withTimestamps();
    }

    public function getName(string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first()
            ?? $this->translations->where('locale', config('app.fallback_locale'))->first();
        return $translation?->name ?? $this->slug;
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeTrending($query)
    {
        return $query->orderByDesc('posts_count');
    }
}
