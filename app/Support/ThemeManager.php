<?php

namespace App\Support;

use App\Models\Setting;

class ThemeManager
{
    public static function presets(): array
    {
        return [
            'red' => [
                'name'          => 'Crimson Red',
                'description'   => 'Bold and urgent — ideal for breaking news.',
                'primary'       => '#DC2626',
                'primary_dark'  => '#B91C1C',
                'primary_hover' => '#991B1B',
                'primary_light' => '#FEF2F2',
                'primary_text'  => '#DC2626',
                'primary_light_text' => '#991B1B',
                'preview_from'  => '#ef4444',
                'preview_to'    => '#DC2626',
            ],
            'blue' => [
                'name'          => 'Ocean Blue',
                'description'   => 'Trustworthy and professional.',
                'primary'       => '#2563EB',
                'primary_dark'  => '#1D4ED8',
                'primary_hover' => '#1E40AF',
                'primary_light' => '#EFF6FF',
                'primary_text'  => '#2563EB',
                'primary_light_text' => '#1D4ED8',
                'preview_from'  => '#3B82F6',
                'preview_to'    => '#2563EB',
            ],
            'green' => [
                'name'          => 'Forest Green',
                'description'   => 'Fresh and balanced for general news.',
                'primary'       => '#16A34A',
                'primary_dark'  => '#15803D',
                'primary_hover' => '#166534',
                'primary_light' => '#F0FDF4',
                'primary_text'  => '#16A34A',
                'primary_light_text' => '#15803D',
                'preview_from'  => '#22C55E',
                'preview_to'    => '#16A34A',
            ],
            'amber' => [
                'name'          => 'Golden Amber',
                'description'   => 'Warm and inviting — great for culture & lifestyle.',
                'primary'       => '#D97706',
                'primary_dark'  => '#B45309',
                'primary_hover' => '#92400E',
                'primary_light' => '#FFFBEB',
                'primary_text'  => '#D97706',
                'primary_light_text' => '#B45309',
                'preview_from'  => '#F59E0B',
                'preview_to'    => '#D97706',
            ],
            'purple' => [
                'name'          => 'Royal Purple',
                'description'   => 'Premium feel for exclusive content.',
                'primary'       => '#7C3AED',
                'primary_dark'  => '#6D28D9',
                'primary_hover' => '#5B21B6',
                'primary_light' => '#F5F3FF',
                'primary_text'  => '#7C3AED',
                'primary_light_text' => '#6D28D9',
                'preview_from'  => '#8B5CF6',
                'preview_to'    => '#7C3AED',
            ],
            'teal' => [
                'name'          => 'Deep Teal',
                'description'   => 'Modern and focused for tech & business news.',
                'primary'       => '#0D9488',
                'primary_dark'  => '#0F766E',
                'primary_hover' => '#115E59',
                'primary_light' => '#F0FDFA',
                'primary_text'  => '#0D9488',
                'primary_light_text' => '#0F766E',
                'preview_from'  => '#14B8A6',
                'preview_to'    => '#0D9488',
            ],
        ];
    }

    public static function active(): string
    {
        return Setting::get('active_theme', 'red', 'theme');
    }

    public static function activeTheme(): array
    {
        $slug = self::active();
        $customs = Setting::get('theme_custom_' . $slug, null, 'theme');

        $theme = self::presets()[$slug] ?? self::presets()['red'];

        if (is_array($customs)) {
            $theme = array_merge($theme, $customs);
        }

        return array_merge($theme, ['slug' => $slug]);
    }

    public static function css(): string
    {
        $t = self::activeTheme();
        $p  = $t['primary'];
        $pd = $t['primary_dark'];
        $ph = $t['primary_hover'];
        $pl = $t['primary_light'];
        $pt = $t['primary_text'];

        if ($t['slug'] === 'red') {
            return ''; // default theme — no overrides needed
        }

        return <<<CSS
:root {
  --color-primary: {$p};
  --color-primary-dark: {$pd};
}
/* Brand colour overrides for theme: {$t['name']} */
.bg-red-600,.hover\\:bg-red-600:hover{background-color:{$p}!important}
.bg-red-700,.hover\\:bg-red-700:hover{background-color:{$pd}!important}
.bg-red-800,.hover\\:bg-red-800:hover{background-color:{$ph}!important}
.bg-red-50,.hover\\:bg-red-50:hover{background-color:{$pl}!important}
.bg-red-100,.hover\\:bg-red-100:hover{background-color:{$pl}!important}
.text-red-600,.hover\\:text-red-600:hover,.group:hover .group-hover\\:text-red-600{color:{$p}!important}
.text-red-700,.hover\\:text-red-700:hover{color:{$pd}!important}
.text-red-400,.dark .dark\\:text-red-400{color:{$p}!important}
.text-red-500{color:{$p}!important}
.border-red-600,.hover\\:border-red-600:hover{border-color:{$p}!important}
.border-red-500{border-color:{$p}!important}
.border-red-200{border-color:{$pl}!important}
.ring-red-500{--tw-ring-color:{$p}!important}
.from-red-500{--tw-gradient-from:{$p}!important}
.via-red-600{--tw-gradient-via:{$pd}!important}
.to-red-500{--tw-gradient-to:{$p}!important}
.focus\\:ring-red-500:focus{--tw-ring-color:{$p}!important}
.focus\\:border-red-500:focus{border-color:{$p}!important}
.bg-red-900\\/20{background-color:color-mix(in srgb,{$p} 20%,transparent)!important}
.dark .dark\\:bg-red-900\\/20{background-color:color-mix(in srgb,{$p} 20%,transparent)!important}
CSS;
    }
}
