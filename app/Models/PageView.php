<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageView extends Model
{
    protected $fillable = [
        'post_id', 'session_id', 'user_id', 'ip_address',
        'user_agent', 'referrer', 'utm_source', 'utm_medium', 'utm_campaign',
        'device_type', 'country_code', 'date',
    ];

    protected $casts = ['date' => 'date'];

    public $timestamps = false;

    public function post(): BelongsTo { return $this->belongsTo(Post::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
