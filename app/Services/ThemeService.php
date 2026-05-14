<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\Cache;

class ThemeService
{
    private ?Theme $activeTheme = null;

    public function getActive(): Theme
    {
        if ($this->activeTheme) {
            return $this->activeTheme;
        }

        $this->activeTheme = Cache::rememberForever('active_theme', function () {
            return Theme::getActive() ?? $this->getDefaultTheme();
        });

        return $this->activeTheme;
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        return $this->getActive()->getSetting($key, $default);
    }

    public function getColorPalette(): array
    {
        return $this->getActive()->color_palette ?? $this->defaultColorPalette();
    }

    public function getTypography(): array
    {
        return $this->getActive()->typography ?? $this->defaultTypography();
    }

    public function getHomepageBlocks(): array
    {
        return $this->getActive()->homepage_blocks ?? $this->defaultHomepageBlocks();
    }

    public function getCssVariables(): string
    {
        $palette = $this->getColorPalette();
        $typography = $this->getTypography();
        $vars = [];

        foreach ($palette as $key => $value) {
            $vars[] = "--color-{$key}: {$value}";
        }

        foreach ($typography as $key => $value) {
            $vars[] = "--font-{$key}: {$value}";
        }

        return ':root { ' . implode('; ', $vars) . ' }';
    }

    private function defaultColorPalette(): array
    {
        return [
            'primary' => '#DC2626',
            'primary-dark' => '#991B1B',
            'secondary' => '#1E293B',
            'accent' => '#F59E0B',
            'background' => '#FFFFFF',
            'background-dark' => '#0F172A',
            'surface' => '#F8FAFC',
            'surface-dark' => '#1E293B',
            'text' => '#0F172A',
            'text-dark' => '#F1F5F9',
            'text-muted' => '#64748B',
            'border' => '#E2E8F0',
            'border-dark' => '#334155',
        ];
    }

    private function defaultTypography(): array
    {
        return [
            'heading' => "'Noto Sans Dhivehi', 'Georgia', serif",
            'body' => "'Noto Sans Dhivehi', 'Inter', sans-serif",
            'mono' => "'JetBrains Mono', monospace",
            'size-base' => '16px',
            'size-sm' => '14px',
            'size-lg' => '18px',
            'size-xl' => '20px',
            'size-2xl' => '24px',
            'size-3xl' => '30px',
            'size-4xl' => '36px',
        ];
    }

    private function defaultHomepageBlocks(): array
    {
        return [
            ['type' => 'hero_slider', 'enabled' => true, 'config' => ['count' => 5, 'autoplay' => true]],
            ['type' => 'breaking_news', 'enabled' => true, 'config' => ['count' => 10]],
            ['type' => 'featured_grid', 'enabled' => true, 'config' => ['count' => 4]],
            ['type' => 'latest_news', 'enabled' => true, 'config' => ['count' => 12]],
            ['type' => 'category_section', 'enabled' => true, 'config' => ['categories' => [], 'count' => 6]],
            ['type' => 'trending', 'enabled' => true, 'config' => ['count' => 8]],
            ['type' => 'video_section', 'enabled' => true, 'config' => ['count' => 4]],
            ['type' => 'newsletter', 'enabled' => true, 'config' => []],
        ];
    }

    private function getDefaultTheme(): Theme
    {
        return Theme::firstOrCreate(
            ['slug' => 'default'],
            [
                'name' => 'Default News Theme',
                'version' => '1.0.0',
                'author' => 'Dhivehi News',
                'is_active' => true,
                'supports_dark_mode' => true,
                'supports_rtl' => true,
                'color_palette' => $this->defaultColorPalette(),
                'typography' => $this->defaultTypography(),
                'homepage_blocks' => $this->defaultHomepageBlocks(),
            ]
        );
    }
}
