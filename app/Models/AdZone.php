<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'placement', 'width', 'height',
        'is_active', 'is_responsive', 'desktop_only', 'mobile_only',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_responsive' => 'boolean',
        'desktop_only' => 'boolean',
        'mobile_only' => 'boolean',
    ];

    public function ads(): HasMany
    {
        return $this->hasMany(Ad::class, 'zone_id');
    }

    public function activeAds(): HasMany
    {
        return $this->ads()->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->where(fn($q) => $q->whereNull('impression_limit')->orWhereColumn('impressions_count', '<', 'impression_limit'))
            ->inRandomOrder();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
