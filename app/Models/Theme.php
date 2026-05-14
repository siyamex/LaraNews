<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'version', 'author', 'description', 'preview_image',
        'is_active', 'supports_dark_mode', 'supports_rtl', 'config',
        'color_palette', 'typography', 'homepage_blocks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'supports_dark_mode' => 'boolean',
        'supports_rtl' => 'boolean',
        'config' => 'array',
        'color_palette' => 'array',
        'typography' => 'array',
        'homepage_blocks' => 'array',
    ];

    public function settings(): HasMany
    {
        return $this->hasMany(ThemeSetting::class);
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        return $this->settings->where('key', $key)->first()?->value ?? $default;
    }

    public function getPreviewImageUrlAttribute(): ?string
    {
        return $this->preview_image ? asset('storage/themes/' . $this->slug . '/' . $this->preview_image) : null;
    }

    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }

    public function activate(): void
    {
        static::query()->update(['is_active' => false]);
        $this->update(['is_active' => true]);
        cache()->forget('active_theme');
    }

    public function duplicate(): self
    {
        $new = $this->replicate();
        $new->name = $this->name . ' (Copy)';
        $new->slug = $this->slug . '-copy-' . time();
        $new->is_active = false;
        $new->save();

        foreach ($this->settings as $setting) {
            $new->settings()->create($setting->only(['key', 'value', 'type']));
        }

        return $new;
    }
}
