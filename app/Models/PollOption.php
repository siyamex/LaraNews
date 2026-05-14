<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    protected $fillable = ['poll_id', 'text', 'votes_count', 'sort_order'];

    public function poll(): BelongsTo { return $this->belongsTo(Poll::class); }
    public function votes(): HasMany { return $this->hasMany(PollVote::class, 'poll_option_id'); }

    public function getPercentage(): float
    {
        $total = $this->poll->total_votes;
        return $total > 0 ? round($this->votes_count / $total * 100, 1) : 0;
    }
}
