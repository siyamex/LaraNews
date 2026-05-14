<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaFolder extends Model
{
    protected $fillable = ['parent_id', 'name', 'slug', 'path'];

    public function parent(): BelongsTo { return $this->belongsTo(MediaFolder::class, 'parent_id'); }
    public function children(): HasMany { return $this->hasMany(MediaFolder::class, 'parent_id'); }
}
