<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RssSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'url', 'logo', 'category_id', 'is_active', 'auto_publish',
        'ai_rewrite', 'default_locale', 'fetch_interval', 'last_fetched_at',
        'items_imported', 'filters',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_publish' => 'boolean',
        'ai_rewrite' => 'boolean',
        'last_fetched_at' => 'datetime',
        'filters' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RssItem::class, 'source_id');
    }

    public function pendingItems(): HasMany
    {
        return $this->items()->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isDueForFetch(): bool
    {
        if ($this->last_fetched_at === null) return true;
        return $this->last_fetched_at->addMinutes($this->fetch_interval)->isPast();
    }
}
