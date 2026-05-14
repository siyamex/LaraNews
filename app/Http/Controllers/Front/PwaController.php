<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class PwaController extends Controller
{
    public function manifest()
    {
        $locale = app()->getLocale();

        return response()->json([
            'name'             => config('app.name'),
            'short_name'       => config('app.name'),
            'description'      => $locale === 'dv'
                ? 'ދިވެހި ހަބަރު — ' . config('app.name')
                : 'Maldives News — ' . config('app.name'),
            'start_url'        => '/' . $locale,
            'scope'            => '/',
            'display'          => 'standalone',
            'display_override' => ['window-controls-overlay', 'standalone'],
            'background_color' => '#ffffff',
            'theme_color'      => '#DC2626',
            'orientation'      => 'portrait-primary',
            'lang'             => $locale,
            'dir'              => $locale === 'dv' ? 'rtl' : 'ltr',
            'icons' => [
                ['src' => '/icons/icon-72.png',   'sizes' => '72x72',   'type' => 'image/png'],
                ['src' => '/icons/icon-96.png',   'sizes' => '96x96',   'type' => 'image/png'],
                ['src' => '/icons/icon-128.png',  'sizes' => '128x128', 'type' => 'image/png'],
                ['src' => '/icons/icon-144.png',  'sizes' => '144x144', 'type' => 'image/png'],
                ['src' => '/icons/icon-152.png',  'sizes' => '152x152', 'type' => 'image/png'],
                ['src' => '/icons/icon-192.png',  'sizes' => '192x192', 'type' => 'image/png'],
                ['src' => '/icons/icon-384.png',  'sizes' => '384x384', 'type' => 'image/png'],
                ['src' => '/icons/icon-512.png',  'sizes' => '512x512', 'type' => 'image/png'],
                ['src' => '/icons/icon-512.png',  'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
            ],
            'shortcuts' => [
                [
                    'name'       => $locale === 'dv' ? 'ބްރޭކިން ނިއުސް' : 'Breaking News',
                    'url'        => '/' . $locale . '/breaking-news',
                    'icons'      => [['src' => '/icons/shortcut-breaking.png', 'sizes' => '96x96']],
                ],
                [
                    'name'       => $locale === 'dv' ? 'ލިއުންތައް' : 'Latest News',
                    'url'        => '/' . $locale . '/news',
                    'icons'      => [['src' => '/icons/shortcut-news.png', 'sizes' => '96x96']],
                ],
            ],
            'categories' => ['news', 'magazine'],
            'prefer_related_applications' => false,
        ])->header('Content-Type', 'application/manifest+json');
    }

    public function serviceWorker()
    {
        return response()->file(public_path('sw.js'), [
            'Content-Type'    => 'application/javascript',
            'Service-Worker-Allowed' => '/',
        ]);
    }

    public function offlinePage()
    {
        return view('front.pwa.offline');
    }
}
