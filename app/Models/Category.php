<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id', 'slug', 'icon', 'cover_image', 'color', 'order',
        'is_featured', 'is_active', 'show_in_menu', 'seo_meta',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
        'seo_meta' => 'array',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function allPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'category_post');
    }

    public function getTranslation(string $field, string $locale = null): ?string
    {
        $locale ??= app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first()
            ?? $this->translations->where('locale', config('app.fallback_locale'))->first();
        return $translation?->$field;
    }

    public function getName(string $locale = null): string
    {
        return $this->getTranslation('name', $locale) ?? $this->slug;
    }

    public function getSlugForLocale(string $locale = null): string
    {
        $slug = $this->getTranslation('slug', $locale) ?? $this->slug;
        return $slug ?: 'category-' . $this->id;
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeForMenu($query)
    {
        return $query->where('show_in_menu', true)->active()->orderBy('order');
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    public function getAncestors(): \Illuminate\Support\Collection
    {
        $ancestors = collect();
        $category = $this;
        while ($category->parent) {
            $ancestors->prepend($category->parent);
            $category = $category->parent;
        }
        return $ancestors;
    }

    public function getBreadcrumbs(): \Illuminate\Support\Collection
    {
        return $this->getAncestors()->push($this);
    }
}
