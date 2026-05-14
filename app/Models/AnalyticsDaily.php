<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsDaily extends Model
{
    protected $table = 'analytics_daily';

    protected $fillable = ['date', 'post_id', 'views', 'unique_visitors', 'avg_time_on_page', 'bounce_rate'];

    protected $casts = [
        'date' => 'date',
        'avg_time_on_page' => 'float',
        'bounce_rate' => 'float',
    ];

    public $timestamps = false;

    public function post(): BelongsTo { return $this->belongsTo(Post::class); }
}
