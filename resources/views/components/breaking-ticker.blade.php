@php
    $locale = app()->getLocale();
    $breaking = cache()->remember('breaking_posts_'.$locale, 300, fn() =>
        \App\Models\Post::breaking()
            ->with(['translations' => fn($q) => $q->where('locale', $locale)])
            ->latest('published_at')
            ->take(10)
            ->get()
    );
@endphp

@if($breaking->count())
<div class="bg-red-600 text-white py-1.5 overflow-hidden" aria-label="{{ __('news.breaking') }}">
    <div class="container mx-auto px-4 flex items-center gap-3">
        {{-- Label --}}
        <span class="shrink-0 bg-white text-red-600 text-xs font-black px-2 py-0.5 rounded uppercase tracking-wider animate-pulse">
            {{ __('news.breaking') }}
        </span>

        {{-- Scrolling ticker --}}
        <div class="flex-1 overflow-hidden">
            <div class="flex items-center gap-8 animate-marquee whitespace-nowrap">
                @foreach($breaking as $post)
                    @php $t = $post->translation($locale); @endphp
                    @if($t)
                    <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}"
                       class="text-sm font-medium hover:text-red-100 transition-colors shrink-0 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-white opacity-60"></span>
                        {{ $t->title }}
                    </a>
                    @endif
                @endforeach
                {{-- Duplicate for seamless loop --}}
                @foreach($breaking as $post)
                    @php $t = $post->translation($locale); @endphp
                    @if($t)
                    <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}"
                       class="text-sm font-medium hover:text-red-100 transition-colors shrink-0 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-white opacity-60"></span>
                        {{ $t->title }}
                    </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.animate-marquee {
    animation: marquee 40s linear infinite;
}
[dir="rtl"] .animate-marquee {
    animation-direction: reverse;
}
.animate-marquee:hover { animation-play-state: paused; }
</style>
@endif
