@extends('layouts.admin')
@section('title', 'Themes')

@section('breadcrumb')
<span>›</span> <span class="text-gray-700 dark:text-gray-300">Themes</span>
@endsection

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-black text-gray-900 dark:text-white">Theme Engine</h1>
        <p class="text-sm text-gray-500 mt-1">Choose a colour theme for your site. Changes apply site-wide immediately.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($themes as $slug => $theme)
        <div class="bg-white dark:bg-gray-800 rounded-2xl border-2 transition-all
                    {{ $active === $slug ? 'border-green-500 shadow-lg shadow-green-500/10' : 'border-gray-200 dark:border-gray-700' }}">

            {{-- Preview strip --}}
            <div class="h-20 rounded-t-2xl overflow-hidden" style="background: linear-gradient(135deg, {{ $theme['preview_from'] }}, {{ $theme['preview_to'] }});">
                <div class="p-3 flex items-center gap-2">
                    <div class="w-5 h-5 bg-white/30 rounded"></div>
                    <div class="flex-1 h-2 bg-white/20 rounded-full"></div>
                    <div class="w-14 h-5 bg-white/30 rounded-full"></div>
                </div>
                <div class="mx-3 mt-1 bg-white/15 rounded-lg p-2 flex items-center gap-2">
                    <div class="w-8 h-8 bg-white/25 rounded shrink-0"></div>
                    <div class="flex-1 space-y-1">
                        <div class="h-1.5 bg-white/35 rounded-full"></div>
                        <div class="h-1.5 bg-white/25 rounded-full w-2/3"></div>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">{{ $theme['name'] }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $theme['description'] }}</p>
                    </div>
                    @if($active === $slug)
                    <span class="shrink-0 inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-semibold rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Active
                    </span>
                    @endif
                </div>

                {{-- Swatches --}}
                <div class="flex items-center gap-1.5 mt-3">
                    <div class="w-5 h-5 rounded-full border-2 border-white shadow-sm ring-1 ring-gray-200 dark:ring-gray-700" style="background:{{ $theme['primary'] }};"></div>
                    <div class="w-5 h-5 rounded-full border-2 border-white shadow-sm ring-1 ring-gray-200 dark:ring-gray-700" style="background:{{ $theme['primary_dark'] }};"></div>
                    <div class="w-5 h-5 rounded-full border-2 border-white shadow-sm ring-1 ring-gray-200 dark:ring-gray-700" style="background:{{ $theme['primary_light'] }};"></div>
                    <code class="ms-auto text-xs text-gray-400 font-mono">{{ $theme['primary'] }}</code>
                </div>

                <div class="flex items-center gap-2 mt-4">
                    @if($active !== $slug)
                    <form action="{{ route('admin.themes.activate', $slug) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full px-3 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-lg hover:opacity-90 transition-opacity">
                            Activate
                        </button>
                    </form>
                    @else
                    <div class="flex-1 px-3 py-2 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm font-semibold rounded-lg text-center">
                        Currently Active
                    </div>
                    @endif
                    <a href="{{ route('admin.themes.customize', $slug) }}"
                       class="px-3 py-2 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Customize
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 flex gap-3">
        <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
        <p class="text-sm text-blue-800 dark:text-blue-200">
            The active theme overrides the accent colour (navigation, buttons, links, badges, progress bar) across all public pages.
            Use <strong>Customize</strong> to fine-tune the exact colour values of any preset.
        </p>
    </div>

</div>
@endsection
