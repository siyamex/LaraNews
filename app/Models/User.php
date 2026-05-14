<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable;
    use TwoFactorAuthenticatable, SoftDeletes, HasRoles;
    use CausesActivity, LogsActivity;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'bio', 'phone',
        'locale', 'timezone', 'theme_preference', 'is_active', 'is_verified_journalist',
        'provider', 'provider_id', 'notification_preferences', 'website',
        'twitter_handle', 'facebook_url',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected $appends = ['profile_photo_url'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'is_active' => 'boolean',
            'is_verified_journalist' => 'boolean',
            'notification_preferences' => 'array',
            'password' => 'hashed',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->dontSubmitEmptyLogs();
    }

    // Relationships
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'bookmarks')->withTimestamps();
    }

    public function readingHistory(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function postPurchases(): HasMany
    {
        return $this->hasMany(PostPurchase::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeJournalists($query)
    {
        return $query->where('is_verified_journalist', true);
    }

    // Helpers
    public function isSubscribed(): bool
    {
        return $this->subscription !== null;
    }

    public function hasPremiumAccess(): bool
    {
        return $this->hasRole(['super_admin', 'admin', 'editor']) || $this->isSubscribed();
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function hasBookmarked(Post $post): bool
    {
        return $this->bookmarks()->where('post_id', $post->id)->exists();
    }

    public function getProfileUrlAttribute(): string
    {
        return route('author.show', ['locale' => $this->locale ?? 'dv', 'username' => $this->username ?? $this->id]);
    }

    public function getIsRtlAttribute(): bool
    {
        return in_array($this->locale, ['dv', 'ar', 'fa', 'he', 'ur']);
    }

    public function canAccessAdmin(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin', 'editor', 'moderator', 'journalist']);
    }
}
