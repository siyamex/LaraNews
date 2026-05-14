@extends('layouts.app')

@section('content')
@php
    $locale = app()->getLocale();
    $isRtl = $locale === 'dv';
    $translation = $post->translation($locale);
@endphp

<div class="container mx-auto px-4 py-6 max-w-7xl">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Article Column --}}
        <article id="article-body" class="lg:col-span-2" itemscope itemtype="https://schema.org/NewsArticle">

            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-5" aria-label="Breadcrumb">
                <a href="{{ route('home', ['locale' => $locale]) }}" class="hover:text-red-600 transition-colors">{{ __('news.home') }}</a>
                <span>›</span>
                @if($post->category)
                <a href="{{ route('category.show', ['locale' => $locale, 'slug' => $post->category->getSlugForLocale($locale)]) }}"
                   class="hover:text-red-600 transition-colors">{{ $post->category->getName($locale) }}</a>
                <span>›</span>
                @endif
                <span class="text-gray-700 dark:text-gray-300 truncate max-w-[200px]">{{ $translation?->title }}</span>
            </nav>

            {{-- Category & Breaking badge --}}
            <div class="flex items-center gap-2 flex-wrap mb-4">
                @if($post->is_breaking)
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-600 text-white text-xs font-black uppercase rounded-full animate-pulse">
                    <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                    {{ __('news.breaking') }}
                </span>
                @endif
                @if($post->category)
                <a href="{{ route('category.show', ['locale' => $locale, 'slug' => $post->category->getSlugForLocale($locale)]) }}"
                   class="inline-flex px-3 py-1 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-xs font-semibold rounded-full hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">
                    {{ $post->category->getName($locale) }}
                </a>
                @endif
                @if($post->is_premium)
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded-full">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    {{ __('news.premium') }}
                </span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-black text-gray-900 dark:text-white leading-tight mb-5"
                itemprop="headline">
                {{ $translation?->title }}
            </h1>

            {{-- Excerpt --}}
            @if($translation?->excerpt)
            <p class="text-lg text-gray-600 dark:text-gray-400 font-light leading-relaxed mb-5 border-s-4 border-red-600 ps-4">
                {{ $translation->excerpt }}
            </p>
            @endif

            {{-- Meta Row --}}
            <div class="flex items-center flex-wrap gap-4 py-4 border-y border-gray-100 dark:border-gray-800 mb-6">
                {{-- Author --}}
                @if($post->user)
                <a href="{{ $post->user->profile_url }}" class="flex items-center gap-3 hover:text-red-600 transition-colors group">
                    <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}"
                         class="w-10 h-10 rounded-full object-cover ring-2 ring-transparent group-hover:ring-red-500 transition-all">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $post->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ __('news.by_author') }}</p>
                    </div>
                </a>
                @endif

                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 ms-auto flex-wrap">
                    <time datetime="{{ $post->published_at?->toIso8601String() }}" itemprop="datePublished">
                        {{ $post->published_at?->format('d M Y, H:i') }}
                    </time>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        {{ $post->reading_time }} {{ __('news.reading_time', ['count' => $post->reading_time]) }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        {{ number_format($post->views_count) }}
                    </span>
                </div>
            </div>

            {{-- Featured Image --}}
            @if($post->featured_image)
            <figure class="mb-7 rounded-2xl overflow-hidden">
                <img src="{{ asset('storage/'.$post->featured_image) }}"
                     alt="{{ $translation?->featured_image_alt ?? $translation?->title }}"
                     class="w-full h-auto object-cover max-h-[500px]"
                     itemprop="image"
                     loading="eager">
                @if($post->featured_image_caption)
                <figcaption class="text-xs text-gray-500 text-center mt-2 italic">
                    {{ $post->featured_image_caption }}
                </figcaption>
                @endif
            </figure>
            @endif

            {{-- Content --}}
            <div id="article-content"
                 class="prose prose-lg dark:prose-invert max-w-none
                        prose-headings:font-black prose-a:text-red-600 prose-a:no-underline hover:prose-a:underline
                        prose-img:rounded-xl prose-blockquote:border-red-600 prose-blockquote:bg-gray-50 prose-blockquote:dark:bg-gray-800 prose-blockquote:py-1 prose-blockquote:px-4 prose-blockquote:rounded-e-xl
                        {{ $isRtl ? 'text-right' : '' }}"
                 itemprop="articleBody">

                @if($post->isAccessibleBy(auth()->user()))
                    {!! $translation?->content !!}
                @else
                    {{-- Free preview + paywall --}}
                    @php
                        $paragraphs = explode('</p>', $translation?->content ?? '');
                        $preview = implode('</p>', array_slice($paragraphs, 0, $post->free_paragraphs)) . '</p>';
                    @endphp
                    <div class="relative">
                        {!! $preview !!}
                        {{-- Fade overlay --}}
                        @if($post->paywall_type?->value === 'fade')
                        <div class="absolute bottom-0 start-0 end-0 h-32 bg-gradient-to-t from-white dark:from-gray-950 to-transparent"></div>
                        @endif
                    </div>
                    {{-- Paywall CTA --}}
                    @include('components.paywall-cta', ['post' => $post])
                @endif
            </div>

            {{-- Source --}}
            @if($post->source_name)
            <div class="mt-6 text-xs text-gray-400 flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                {{ $locale === 'dv' ? 'ރިޕޯޓް ކޮށްފައި:' : 'Source:' }}
                @if($post->source_url)
                <a href="{{ $post->source_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:underline">{{ $post->source_name }}</a>
                @else
                {{ $post->source_name }}
                @endif
            </div>
            @endif

            {{-- Tags --}}
            @if($post->tags->count())
            <div class="mt-6 flex flex-wrap gap-2">
                <span class="text-sm text-gray-500 font-medium">{{ __('news.tags') }}:</span>
                @foreach($post->tags as $tag)
                <a href="{{ route('tag.show', ['locale' => $locale, 'slug' => $tag->slug]) }}"
                   class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-sm rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                    #{{ $tag->getName($locale) }}
                </a>
                @endforeach
            </div>
            @endif

            {{-- Share & Actions --}}
            <div class="mt-7 flex items-center flex-wrap gap-3 py-4 border-y border-gray-100 dark:border-gray-800">
                <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">{{ __('news.share') }}:</span>
                @include('components.share-buttons', ['post' => $post, 'translation' => $translation])

                <div class="ms-auto flex items-center gap-3">
                    {{-- Bookmark --}}
                    <livewire:front.bookmark-button :post="$post" />
                    {{-- Reactions --}}
                    <livewire:front.reaction-bar :post="$post" />
                </div>
            </div>

            {{-- FAQ --}}
            @if($translation?->faq)
            <div class="mt-8">
                <h2 class="text-xl font-black text-gray-900 dark:text-white mb-4">
                    {{ $locale === 'dv' ? 'ގިނައިން ކުރެވޭ ސުވާލު' : 'Frequently Asked Questions' }}
                </h2>
                <div class="space-y-3" x-data="{ open: null }">
                    @foreach($translation->faq as $idx => $faq)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                        <button @click="open === {{ $idx }} ? open = null : open = {{ $idx }}"
                                class="flex items-center justify-between w-full text-start px-5 py-4 text-sm font-semibold text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            {{ $faq['question'] }}
                            <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="open === {{ $idx }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === {{ $idx }}" x-collapse class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50">
                            {!! $faq['answer'] !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- AI Summary --}}
            @if($post->ai_summary)
            <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-5 border border-blue-100 dark:border-blue-800">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                    <span class="text-sm font-bold text-blue-700 dark:text-blue-300">{{ $locale === 'dv' ? 'AI ހުލާސާ' : 'AI Summary' }}</span>
                </div>
                <p class="text-sm text-blue-800 dark:text-blue-200 leading-relaxed">{{ $post->ai_summary }}</p>
            </div>
            @endif

            {{-- In-content Ad --}}
            @include('components.ad-zone', ['placement' => 'in_content'])

            {{-- Comments --}}
            @if($post->allow_comments)
            <div class="mt-10">
                <livewire:front.comments :post="$post" />
            </div>
            @endif
        </article>

        {{-- Sidebar --}}
        <aside class="space-y-6">
            @include('components.ad-zone', ['placement' => 'sidebar'])

            {{-- Related Posts --}}
            @if($related->count())
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5">
                <h3 class="font-black text-gray-900 dark:text-white mb-4">{{ __('news.related') }}</h3>
                <div class="space-y-4">
                    @foreach($related->take(5) as $relPost)
                    @include('components.post-card', ['post' => $relPost, 'variant' => 'mini'])
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Author Box --}}
            @if($post->user)
            @include('components.author-box', ['user' => $post->user])
            @endif

            @include('components.ad-zone', ['placement' => 'sidebar'])
        </aside>
    </div>
</div>
@endsection
