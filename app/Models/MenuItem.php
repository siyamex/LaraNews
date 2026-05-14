<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id', 'parent_id', 'type', 'label',
        'url', 'reference_id', 'target', 'icon',
        'sort_order', 'is_active', 'css_class',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function menu(): BelongsTo { return $this->belongsTo(Menu::class); }
    public function parent(): BelongsTo { return $this->belongsTo(MenuItem::class, 'parent_id'); }
    public function children(): HasMany { return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order'); }
}
