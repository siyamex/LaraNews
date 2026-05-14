<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostPurchase extends Model
{
    protected $fillable = ['user_id', 'post_id', 'payment_id', 'amount', 'currency'];

    protected $casts = ['amount' => 'decimal:2'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function post(): BelongsTo { return $this->belongsTo(Post::class); }
    public function payment(): BelongsTo { return $this->belongsTo(Payment::class); }
}
