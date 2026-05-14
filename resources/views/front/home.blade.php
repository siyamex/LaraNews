@extends('layouts.app')

@section('content')
@php $locale = app()->getLocale(); $isRtl = $locale === 'dv'; @endphp

{{-- =========================================================
    HERO SLIDER
    ========================================================= --}}
@if($heroSlider->count())
<section class="relative bg-gray-900 overflow-hidden" aria-label="Featured Stories">
    <div x-data="{ current: 0, total: {{ $heroSlider->count() }}, autoplay: null }"
         x-init="autoplay = setInterval(() => { current = (current + 1) % total }, 5000)"
         @mouseenter="clearInterval(autoplay)" @mouseleave="autoplay = setInterval(() => { current = (current + 1) % total }, 5000)"
         class="relative h-[480px] md:h-[560px]">

        @foreach($heroSlider as $idx => $post)
        @php $t = $post->translation($locale); @endphp
        @if($t)
        <div x-show="current === {{ $idx }}"
             x-transition:enter="transition-opacity duration-700"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             @if($idx > 0) style="display:none" @endif
             class="absolute inset-0">

            {{-- Background image --}}
            @if($post->featured_image)
            <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $t->featured_image_alt ?? $t->title }}"
                 class="absolute inset-0 w-full h-full object-cover" loading="{{ $idx === 0 ? 'eager' : 'lazy' }}">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent"></div>

            <div class="absolute bottom-0 start-0 end-0 p-6 md:p-10 lg:p-14">
                <div class="max-w-3xl">
                    {{-- Category badge --}}
                    @if($post->category)
                    <a href="{{ route('category.show', ['locale'=>$locale,'slug'=>$post->category->getSlugForLocale($locale)]) }}"
                       class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs font-bold uppercase rounded mb-3 hover:bg-red-700 transition-colors">
                        {{ $post->category->getName($locale) }}
                    </a>
                    @endif
                    @if($post->is_breaking)
                    <span class="inline-flex items-center px-3 py-1 bg-yellow-500 text-black text-xs font-black uppercase rounded mb-3 ms-2 animate-pulse">
                        {{ __('news.breaking') }}
                    </span>
                    @endif

                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-white leading-tight mb-3">
                        <a href="{{ route('news.show', ['locale'=>$locale,'slug'=>$t->slug]) }}" class="hover:text-red-300 transition-colors">
                            {{ $t->title }}
                        </a>
                    </h2>
                    <p class="text-gray-300 text-sm md:text-base line-clamp-2 mb-4">{{ $t->excerpt }}</p>

                    <div class="flex items-center gap-4 text-xs text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $post->user?->name }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $post->published_at?->diffForHumans() }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            {{ number_format($post->views_count) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach

        {{-- Controls --}}
        <div class="absolute bottom-4 end-4 md:bottom-8 md:end-8 flex items-center gap-2 z-10">
            @foreach($heroSlider as $idx => $post)
            <button @click="current={{ $idx }}" class="w-2 h-2 rounded-full transition-all"
                    :class="current==={{ $idx }} ? 'bg-white w-6' : 'bg-white/50'"></button>
            @endforeach
        </div>

        {{-- Arrows --}}
        <button @click="current = (current - 1 + total) % total"
                class="absolute start-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center bg-black/40 hover:bg-black/60 text-white rounded-full transition-all backdrop-blur-sm z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $isRtl ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7' }}"/></svg>
        </button>
        <button @click="current = (current + 1) % total"
                class="absolute end-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center bg-black/40 hover:bg-black/60 text-white rounded-full transition-all backdrop-blur-sm z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $isRtl ? 'M15 19l-7-7 7-7' : 'M9 5l7 7-7 7' }}"/></svg>
        </button>
    </div>
</section>
@endif

{{-- =========================================================
    AD ZONE: HEADER BANNER
    ========================================================= --}}
@include('components.ad-zone', ['placement' => 'header'])

{{-- =========================================================
    MAIN CONTENT GRID
    ========================================================= --}}
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left: Latest News (2/3 width) --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Featured Grid --}}
            @if($featured->count())
            <section>
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="w-1 h-6 bg-red-600 rounded-full"></span>
                        {{ $locale === 'dv' ? 'ފީޗަރ ހަބަރު' : 'Featured Stories' }}
                    </h2>
                </div>
                {{-- Hero card --}}
                @include('components.post-card', ['post' => $featured->first(), 'variant' => 'featured'])
                {{-- Sub-cards --}}
                @if($featured->count() > 1)
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-3">
                    @foreach($featured->skip(1)->take(3) as $post)
                    @include('components.post-card', ['post' => $post, 'variant' => 'grid'])
                    @endforeach
                </div>
                @endif
            </section>
            @endif

            {{-- Latest News --}}
            <section>
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="w-1 h-6 bg-blue-600 rounded-full"></span>
                        {{ __('news.latest') }}
                    </h2>
                    <a href="{{ route('news.index', ['locale' => $locale]) }}" class="text-sm text-red-600 hover:text-red-700 font-medium">
                        {{ $locale === 'dv' ? 'ހުރިހާ ހަބަރު' : 'All News' }} →
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach($latestNews as $post)
                    @include('components.post-card', ['post' => $post, 'variant' => 'list'])
                    @endforeach
                </div>
                {{-- Load More --}}
                <div class="mt-6 text-center">
                    <livewire:front.load-more-posts :loaded="$latestNews->count()" />
                </div>
            </section>

            {{-- Ad: In-content --}}
            @include('components.ad-zone', ['placement' => 'in_content'])

            {{-- Category Sections --}}
            @foreach($categorySections as $section)
            @include('components.category-section', ['category' => $section['category'], 'posts' => $section['posts']])
            @endforeach

            {{-- Video Section --}}
            @if($videos->count())
            <section>
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="w-1 h-6 bg-purple-600 rounded-full"></span>
                        {{ $locale === 'dv' ? 'ވިޑިއޯ' : 'Videos' }}
                    </h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($videos->take(4) as $post)
                    @include('components.post-card', ['post' => $post, 'variant' => 'video'])
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        {{-- Right Sidebar (1/3 width) --}}
        <aside class="space-y-6">

            {{-- Ad: Sidebar --}}
            @include('components.ad-zone', ['placement' => 'sidebar'])

            {{-- Trending --}}
            @if($trending->count())
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5">
                <h3 class="font-black text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/></svg>
                    {{ __('news.trending') }}
                </h3>
                <ol class="space-y-3">
                    @foreach($trending->take(8) as $idx => $post)
                    @php $t = $post->translation($locale); @endphp
                    @if($t)
                    <li class="flex gap-3">
                        <span class="text-2xl font-black text-gray-200 dark:text-gray-700 shrink-0 leading-none">{{ str_pad($idx+1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <a href="{{ route('news.show', ['locale'=>$locale,'slug'=>$t->slug]) }}"
                               class="text-sm font-semibold text-gray-800 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 transition-colors line-clamp-2 leading-snug">
                                {{ $t->title }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">{{ number_format($post->views_count) }} {{ __('news.views') }}</p>
                        </div>
                    </li>
                    @endif
                    @endforeach
                </ol>
            </div>
            @endif

            {{-- Tags Cloud --}}
            @if($popularTags->count())
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5">
                <h3 class="font-black text-gray-900 dark:text-white mb-4">{{ __('news.tags') }}</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($popularTags as $tag)
                    <a href="{{ route('tag.show', ['locale'=>$locale,'slug'=>$tag->slug]) }}"
                       class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-full hover:bg-red-100 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                        #{{ $tag->getName($locale) }}
                        <span class="text-gray-400">({{ $tag->posts_count }})</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Weather Widget --}}
            @if(\App\Models\Setting::get('show_weather', false, 'widgets'))
            <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white">
                <h3 class="font-bold mb-3 opacity-80 text-sm">{{ $locale === 'dv' ? 'މޫސުން' : 'Weather' }} — Malé</h3>
                <livewire:front.weather-widget />
            </div>
            @endif

            {{-- Prayer Times Widget --}}
            @if(\App\Models\Setting::get('show_prayer_times', true, 'widgets'))
            <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-2xl p-5 text-white">
                <h3 class="font-bold mb-3 text-sm">{{ $locale === 'dv' ? 'ނަމާދު ވަގުތު' : 'Prayer Times' }}</h3>
                <livewire:front.prayer-times />
            </div>
            @endif

            {{-- Ad: Sidebar 2 --}}
            @include('components.ad-zone', ['placement' => 'sidebar'])
        </aside>
    </div>
</div>
@endsection
