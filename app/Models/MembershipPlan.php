<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'interval', 'price', 'currency',
        'trial_days', 'is_active', 'is_featured', 'features', 'limits',
        'stripe_price_id', 'paypal_plan_id', 'sort_order',
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'features' => 'array',
        'limits' => 'array',
        'price' => 'decimal:2',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' ' . $this->currency;
    }

    public function getIntervalLabelAttribute(): string
    {
        return match($this->interval) {
            'monthly' => __('Per Month'),
            'yearly' => __('Per Year'),
            'lifetime' => __('Lifetime'),
            default => $this->interval,
        };
    }

    public function isFree(): bool
    {
        return $this->price == 0;
    }
}
