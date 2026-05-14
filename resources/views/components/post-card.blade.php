@php
    $locale = app()->getLocale();
    $t = $post->translation($locale);
    $variant = $variant ?? 'list';
    $cat = $post->category;
@endphp

@if($variant === 'list')
<article class="group flex gap-4 p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-gray-200 dark:hover:border-gray-600 transition-all duration-200">
    @if($post->featured_image)
    <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t?->slug]) }}"
       class="shrink-0 w-28 sm:w-36 self-start">
        <div class="aspect-[4/3] rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700">
            <img src="{{ asset('storage/' . $post->featured_image) }}"
                 alt="{{ $t?->featured_image_alt ?? $t?->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 loading="lazy">
        </div>
    </a>
    @endif
    <div class="flex-1 min-w-0 flex flex-col justify-between gap-2 py-0.5">
        <div>
            @if($cat)
            <a href="{{ route('category.show', ['locale' => $locale, 'slug' => $cat->getSlugForLocale($locale)]) }}"
               class="inline-block text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wide hover:text-red-700 dark:hover:text-red-300 transition-colors mb-1.5">
                {{ $cat->getName($locale) }}
            </a>
            @endif
            <h3 class="font-bold text-gray-900 dark:text-white text-sm sm:text-base leading-snug line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t?->slug]) }}">
                    {{ $t?->title }}
                </a>
            </h3>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            @if($post->user)
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $post->user->name }}</span>
            <span class="text-gray-300 dark:text-gray-600">·</span>
            @endif
            <time class="text-xs text-gray-400">{{ $post->published_at?->diffForHumans() }}</time>
            @if($post->is_premium)
            <span class="ms-auto px-1.5 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded-md">PRO</span>
            @endif
        </div>
    </div>
</article>

@elseif($variant === 'featured')
<article class="group relative rounded-2xl overflow-hidden bg-gray-900 shadow-md hover:shadow-xl transition-all duration-300">
    <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t?->slug]) }}" class="block aspect-video">
        @if($post->featured_image)
        <img src="{{ asset('storage/' . $post->featured_image) }}"
             alt="{{ $t?->title }}"
             class="w-full h-full object-cover opacity-85 group-hover:opacity-70 group-hover:scale-105 transition-all duration-500"
             loading="lazy">
        @else
        <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900"></div>
        @endif
    </a>
    <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-900/50 to-transparent pointer-events-none"></div>
    <div class="absolute bottom-0 start-0 end-0 p-4">
        <div class="flex items-center gap-2 mb-2 flex-wrap">
            @if($cat)
            <a href="{{ route('category.show', ['locale' => $locale, 'slug' => $cat->getSlugForLocale($locale)]) }}"
               class="inline-block px-2 py-0.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase rounded transition-colors">
                {{ $cat->getName($locale) }}
            </a>
            @endif
            @if($post->is_breaking)
            <span class="inline-block px-2 py-0.5 bg-yellow-500 text-black text-xs font-black uppercase rounded animate-pulse">
                {{ __('news.breaking') }}
            </span>
            @endif
        </div>
        <h3 class="font-bold text-white text-sm sm:text-base leading-snug line-clamp-3">
            <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t?->slug]) }}"
               class="hover:text-red-300 transition-colors">
                {{ $t?->title }}
            </a>
        </h3>
        <div class="flex items-center gap-2 mt-1.5 text-xs text-gray-400">
            @if($post->user)<span>{{ $post->user->name }}</span><span>·</span>@endif
            <time>{{ $post->published_at?->diffForHumans() }}</time>
        </div>
    </div>
</article>

@elseif($variant === 'mini')
<article class="flex gap-3 group">
    @if($post->featured_image)
    <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t?->slug]) }}" class="shrink-0">
        <div class="w-16 h-14 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700">
            <img src="{{ asset('storage/' . $post->featured_image) }}"
                 alt="{{ $t?->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 loading="lazy">
        </div>
    </a>
    @endif
    <div class="flex-1 min-w-0">
        <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 leading-snug line-clamp-2">
            <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t?->slug]) }}"
               class="hover:text-red-600 dark:hover:text-red-400 transition-colors">
                {{ $t?->title }}
            </a>
        </h4>
        <p class="text-xs text-gray-400 mt-1">{{ $post->published_at?->diffForHumans() }}</p>
    </div>
</article>

@elseif($variant === 'video')
<article class="group relative rounded-2xl overflow-hidden bg-gray-900 shadow hover:shadow-lg transition-all duration-300">
    <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t?->slug]) }}" class="block aspect-video">
        @if($post->featured_image)
        <img src="{{ asset('storage/' . $post->featured_image) }}"
             alt="{{ $t?->title }}"
             class="w-full h-full object-cover opacity-75 group-hover:opacity-60 transition-opacity"
             loading="lazy">
        @else
        <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900"></div>
        @endif
    </a>
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-200 shadow-lg">
            <svg class="w-5 h-5 text-red-600 ms-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
        </div>
    </div>
    <div class="absolute bottom-0 start-0 end-0 p-3 bg-gradient-to-t from-gray-900/90 to-transparent">
        <h3 class="font-bold text-white text-sm line-clamp-2 leading-snug">
            <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t?->slug]) }}">{{ $t?->title }}</a>
        </h3>
    </div>
</article>

@elseif($variant === 'grid')
<article class="group flex flex-col bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md hover:border-gray-200 dark:hover:border-gray-600 transition-all duration-200">
    @if($post->featured_image)
    <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t?->slug]) }}" class="block aspect-video overflow-hidden bg-gray-100 dark:bg-gray-700">
        <img src="{{ asset('storage/' . $post->featured_image) }}"
             alt="{{ $t?->title }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
             loading="lazy">
    </a>
    @endif
    <div class="flex-1 flex flex-col p-4">
        @if($cat)
        <a href="{{ route('category.show', ['locale' => $locale, 'slug' => $cat->getSlugForLocale($locale)]) }}"
           class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wide mb-1.5 hover:text-red-700 transition-colors">
            {{ $cat->getName($locale) }}
        </a>
        @endif
        <h3 class="font-bold text-gray-900 dark:text-white text-sm leading-snug line-clamp-2 flex-1">
            <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t?->slug]) }}"
               class="hover:text-red-600 dark:hover:text-red-400 transition-colors">
                {{ $t?->title }}
            </a>
        </h3>
        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-400">
            @if($post->user)<span>{{ $post->user->name }}</span><span>·</span>@endif
            <time>{{ $post->published_at?->diffForHumans() }}</time>
            @if($post->is_premium)
            <span class="ms-auto px-1.5 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded-md">PRO</span>
            @endif
        </div>
    </div>
</article>
@endif
