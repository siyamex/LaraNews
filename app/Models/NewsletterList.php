<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NewsletterList extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_default', 'is_public', 'subscribers_count'];

    protected $casts = ['is_default' => 'boolean', 'is_public' => 'boolean'];

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(NewsletterSubscriber::class, 'newsletter_list_subscriber')
            ->withTimestamps();
    }

    public function campaigns(): HasMany { return $this->hasMany(NewsletterCampaign::class); }
}
