<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    protected $fillable = ['post_id', 'question', 'ends_at', 'is_multiple', 'show_results', 'total_votes'];

    protected $casts = ['ends_at' => 'datetime', 'is_multiple' => 'boolean', 'show_results' => 'boolean'];

    public function post(): BelongsTo { return $this->belongsTo(Post::class); }
    public function options(): HasMany { return $this->hasMany(PollOption::class); }
    public function votes(): HasMany { return $this->hasMany(PollVote::class); }

    public function isExpired(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }
}
