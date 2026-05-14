<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type', 'is_public'];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public static function get(string $key, mixed $default = null, string $group = 'general'): mixed
    {
        $cacheKey = "setting:{$group}:{$key}";
        return Cache::rememberForever($cacheKey, function () use ($key, $group, $default) {
            $setting = static::where('group', $group)->where('key', $key)->first();
            return $setting ? static::castValue($setting->value, $setting->type) : $default;
        });
    }

    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'text'): void
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
            $type = 'json';
        }

        static::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $value, 'type' => $type]
        );

        Cache::forget("setting:{$group}:{$key}");
    }

    public static function getGroup(string $group): array
    {
        return Cache::rememberForever("settings_group:{$group}", function () use ($group) {
            return static::where('group', $group)->get()
                ->mapWithKeys(fn($s) => [$s->key => static::castValue($s->value, $s->type)])
                ->toArray();
        });
    }

    public static function flushGroup(string $group): void
    {
        Cache::forget("settings_group:{$group}");
    }

    private static function castValue(mixed $value, string $type): mixed
    {
        return match($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'json', 'array' => json_decode($value, true),
            default => $value,
        };
    }
}
