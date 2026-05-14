<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'dv' ? 'rtl' : 'ltr' }}" class="scroll-smooth"
      x-data="{ darkMode: document.documentElement.classList.contains('dark') }"
      x-init="$watch('darkMode', v => {
          v ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark');
          localStorage.setItem('theme', v ? 'dark' : 'light');
      })"
      @toggle-dark.window="darkMode=!darkMode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>{{ $seo['title'] ?? config('app.name') }}</title>
    <meta name="description" content="{{ $seo['description'] ?? '' }}">
    <meta name="robots" content="{{ $seo['robots'] ?? 'index,follow' }}">
    @isset($seo['canonical'])<link rel="canonical" href="{{ $seo['canonical'] }}">@endisset
    <meta property="og:title" content="{{ $seo['og_title'] ?? $seo['title'] ?? config('app.name') }}">
    <meta property="og:description" content="{{ $seo['og_description'] ?? $seo['description'] ?? '' }}">
    <meta property="og:image" content="{{ isset($seo['og_image']) ? asset('storage/'.$seo['og_image']) : asset('images/og-default.jpg') }}">
    <meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:type" content="{{ $seo['og_type'] ?? 'website' }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['og_title'] ?? $seo['title'] ?? '' }}">
    <meta name="twitter:description" content="{{ $seo['og_description'] ?? $seo['description'] ?? '' }}">
    <meta name="twitter:image" content="{{ isset($seo['og_image']) ? asset('storage/'.$seo['og_image']) : asset('images/og-default.jpg') }}">
    @isset($hreflang)@foreach($hreflang as $lang => $url)<link rel="alternate" hreflang="{{ $lang }}" href="{{ $url }}">@endforeach@endisset
    @isset($seo['article_published_time'])
    <meta property="article:published_time" content="{{ $seo['article_published_time'] }}">
    <meta property="article:modified_time" content="{{ $seo['article_modified_time'] ?? '' }}">
    <meta property="article:author" content="{{ $seo['article_author'] ?? '' }}">
    @endisset

    {{-- PWA --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <meta name="theme-color" content="#DC2626">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }}" href="{{ route('feed') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Dhivehi:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        (function(){var t=localStorage.getItem('theme');if(t==='dark'||(t===null&&window.matchMedia('(prefers-color-scheme: dark)').matches)){document.documentElement.classList.add('dark');}})();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @php $themeCss = \App\Support\ThemeManager::css(); @endphp
    @if($themeCss)
    <style id="site-theme">{!! $themeCss !!}</style>
    @endif

    @php
        $rawFonts   = \App\Models\Setting::where('key', 'custom_fonts')->where('group', 'fonts')->value('value');
        $customFonts = $rawFonts ? (json_decode($rawFonts, true) ?? []) : [];
        $headingFont = \App\Models\Setting::get('heading_font', '', 'fonts');
        $bodyFont    = \App\Models\Setting::get('body_font', '', 'fonts');
        $fontCss = '';
        foreach ($customFonts as $f) {
            $fontName = htmlspecialchars($f['name'], ENT_QUOTES);
            $fontUrl  = asset('storage/' . $f['file']);
            $fontCss .= "@font-face{font-family:'{$fontName}';src:url('{$fontUrl}')format('{$f['format']}');font-weight:{$f['weight']};font-style:{$f['style']};font-display:swap;}\n";
        }
        if ($headingFont) {
            $hf = htmlspecialchars($headingFont, ENT_QUOTES);
            $fontCss .= "h1,h2,h3,h4,h5,h6{font-family:\"{$hf}\",sans-serif!important;}\n";
        }
        if ($bodyFont) {
            $bf = htmlspecialchars($bodyFont, ENT_QUOTES);
            $fontCss .= "body{font-family:\"{$bf}\",sans-serif!important;}\n";
        }
    @endphp
    @if($fontCss)
    <style id="custom-fonts">{!! $fontCss !!}</style>
    @endif

    @stack('head')

    {{-- JSON-LD --}}
    @isset($schemas)@foreach($schemas as $schema)
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
    @endforeach@endisset
</head>
<body class="font-sans bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 antialiased">

    <x-reading-progress />

    @include('components.breaking-ticker')
    @include('components.header')
    @include('components.mobile-menu')
    @include('components.search-modal')

    <main id="main">{{ $slot ?? '' }}@yield('content')</main>

    @include('components.footer')
    @include('components.cookie-consent')
    <x-back-to-top />

    <script>
        if('serviceWorker' in navigator){
            window.addEventListener('load',()=>navigator.serviceWorker.register('/sw.js').catch(()=>{}));
        }
    </script>
    @stack('scripts')
    @livewireScripts
</body>
</html>
