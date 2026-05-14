<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = ['slug', 'template', 'status', 'user_id', 'order', 'show_in_menu'];

    protected $casts = ['show_in_menu' => 'boolean'];

    public function translations(): HasMany { return $this->hasMany(PageTranslation::class); }
    public function translation(?string $locale = null): ?PageTranslation
    {
        $locale ??= app()->getLocale();
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', config('app.fallback_locale'));
    }
}
