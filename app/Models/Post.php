<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\PostStatus;
use App\Enums\PostType;
use App\Enums\PaywallType;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'uuid', 'user_id', 'category_id', 'type', 'status', 'featured_image',
        'featured_image_caption', 'featured_image_alt', 'video_url', 'audio_url',
        'is_featured', 'is_breaking', 'is_trending', 'is_pinned', 'is_premium',
        'paywall_type', 'free_paragraphs', 'published_at', 'scheduled_at', 'meta',
        'schema_markup', 'content_blocks', 'source_url', 'source_name',
        'allow_comments', 'allow_reactions', 'ai_summary', 'ai_tags', 'poll_id',
        'reading_time',
    ];

    protected $casts = [
        'status'        => PostStatus::class,
        'type'          => PostType::class,
        'paywall_type'  => PaywallType::class,
        'is_featured'   => 'boolean',
        'is_breaking'   => 'boolean',
        'is_trending'   => 'boolean',
        'is_pinned'     => 'boolean',
        'is_premium'    => 'boolean',
        'allow_comments'  => 'boolean',
        'allow_reactions' => 'boolean',
        'published_at'  => 'datetime',
        'scheduled_at'  => 'datetime',
        'meta'          => 'array',
        'schema_markup' => 'array',
        'content_blocks'=> 'array',
        'ai_tags'       => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->uuid ??= Str::uuid()->toString();
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_post')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function translation(string $locale = null): ?PostTranslation
    {
        $locale ??= app()->getLocale();
        return $this->translations->where('locale', $locale)->first()
            ?? $this->translations->where('locale', config('app.fallback_locale'))->first();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(PostRevision::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_authors')
            ->withPivot('role', 'order')
            ->withTimestamps()
            ->orderByPivot('order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag')->withTimestamps();
    }

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::Published)->where('published_at', '<=', now());
    }

    public function scopeBreaking($query)
    {
        return $query->where('is_breaking', true)->published();
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->published();
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true)->published();
    }

    public function scopeForLocale($query, string $locale)
    {
        return $query->whereHas('translations', fn($q) => $q->where('locale', $locale));
    }

    public function scopeLatestPublished($query)
    {
        return $query->published()->orderByDesc('published_at');
    }

    // Helpers
    public function getTitle(string $locale = null): string
    {
        return $this->translation($locale)?->title ?? '';
    }

    public function getSlug(string $locale = null): string
    {
        return $this->translation($locale)?->slug ?? '';
    }

    public function getExcerpt(string $locale = null): string
    {
        return $this->translation($locale)?->excerpt ?? '';
    }

    public function getContent(string $locale = null): string
    {
        return $this->translation($locale)?->content ?? '';
    }

    public function getUrlAttribute(): string
    {
        $locale = app()->getLocale();
        $slug = $this->getSlug($locale);
        return route('news.show', ['locale' => $locale, 'slug' => $slug]);
    }

    public function getReadingTimeAttribute(): int
    {
        if ($this->attributes['reading_time'] ?? 0) {
            return $this->attributes['reading_time'];
        }
        $wordCount = str_word_count(strip_tags($this->getContent()));
        return max(1, (int) ceil($wordCount / 200));
    }

    public function isAccessibleBy(?User $user): bool
    {
        if (!$this->is_premium) {
            return true;
        }
        if ($user === null) {
            return false;
        }
        return $user->hasPremiumAccess() || $user->postPurchases()->where('post_id', $this->id)->exists();
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // Meilisearch / Scout
    public function toSearchableArray(): array
    {
        $translation = $this->translation();
        return [
            'id' => $this->id,
            'title' => $translation?->title,
            'excerpt' => $translation?->excerpt,
            'content' => strip_tags($translation?->content ?? ''),
            'type' => $this->type?->value,
            'status' => $this->status?->value,
            'category' => $this->category?->getTranslation('name', app()->getLocale()),
            'published_at' => $this->published_at?->timestamp,
            'views_count' => $this->views_count,
            'is_featured' => $this->is_featured,
            'is_breaking' => $this->is_breaking,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status === PostStatus::Published;
    }
}
