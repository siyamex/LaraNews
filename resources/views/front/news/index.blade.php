@extends('layouts.app')

@section('content')
@php $locale = app()->getLocale(); $isRtl = $locale === 'dv'; @endphp

<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Main --}}
        <div class="lg:col-span-2">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-1.5 h-8 bg-red-600 rounded-full"></span>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white">
                    {{ $locale === 'dv' ? 'ހުރިހާ ހަބަރު' : 'All News' }}
                </h1>
                <span class="text-sm text-gray-400 font-normal">{{ $posts->total() }} {{ $locale === 'dv' ? 'ހަބަރު' : 'stories' }}</span>
            </div>

            <div class="space-y-4">
                @forelse($posts as $post)
                @include('components.post-card', ['post' => $post, 'variant' => 'list'])
                @empty
                <div class="text-center py-20">
                    <p class="text-gray-400 text-lg">{{ $locale === 'dv' ? 'ހަބަރެއް ނެތް' : 'No stories found.' }}</p>
                </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-6">
            @include('components.ad-zone', ['placement' => 'sidebar'])

            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5">
                <h3 class="font-black text-gray-900 dark:text-white mb-4">{{ __('news.trending') }}</h3>
                @include('components.trending-mini', ['locale' => $locale])
            </div>

            @include('components.ad-zone', ['placement' => 'sidebar'])
        </aside>
    </div>
</div>
@endsection
