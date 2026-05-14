<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LaraNews') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

<div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">

    {{-- Logo / Brand --}}
    <a href="{{ url('/') }}" class="flex flex-col items-center mb-8 group">
        <div class="w-12 h-12 bg-red-600 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-red-700 transition-colors shadow-lg">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
            </svg>
        </div>
        <span class="text-xl font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
    </a>

    {{-- Card --}}
    {{ $slot }}

    {{-- Footer --}}
    <p class="mt-8 text-xs text-gray-400 dark:text-gray-600">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </p>
</div>

@livewireScripts
</body>
</html>
