<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsletterCampaign extends Model
{
    protected $fillable = [
        'newsletter_list_id', 'user_id',
        'subject', 'preheader', 'content', 'template',
        'status', 'scheduled_at', 'sent_at',
        'recipients_count', 'opens_count', 'clicks_count', 'bounces_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at'      => 'datetime',
    ];

    public function list(): BelongsTo { return $this->belongsTo(NewsletterList::class, 'newsletter_list_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function getOpenRateAttribute(): float
    {
        return $this->recipients_count > 0
            ? round($this->opens_count / $this->recipients_count * 100, 1)
            : 0;
    }
}
