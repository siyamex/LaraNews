<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RssItem extends Model
{
    protected $fillable = [
        'rss_source_id', 'guid', 'title', 'content', 'excerpt',
        'url', 'image_url', 'author', 'published_at',
        'status', 'post_id', 'ai_rewritten',
    ];

    protected $casts = [
        'published_at'  => 'datetime',
        'ai_rewritten'  => 'boolean',
    ];

    public function source(): BelongsTo { return $this->belongsTo(RssSource::class, 'rss_source_id'); }
    public function post(): BelongsTo { return $this->belongsTo(Post::class); }
}
