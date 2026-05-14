<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdImpression extends Model
{
    protected $fillable = ['ad_id', 'post_id', 'ip_address', 'user_agent', 'is_click', 'user_id'];

    public function ad(): BelongsTo { return $this->belongsTo(Ad::class); }
    public function post(): BelongsTo { return $this->belongsTo(Post::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
