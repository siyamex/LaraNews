@props(['posts'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
        <span class="text-red-600 font-bold text-sm">🔥</span>
        <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ __('Trending') }}</h3>
    </div>
    <div class="divide-y divide-gray-50 dark:divide-gray-700">
        @foreach($posts as $idx => $post)
        @php $trans = $post->translations->where('locale', app()->getLocale())->first() ?? $post->translations->first(); @endphp
        @if($trans)
        <a href="{{ route('news.show', ['locale' => app()->getLocale(), 'slug' => $trans->slug]) }}"
           class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
            <span class="text-2xl font-black text-gray-200 dark:text-gray-700 leading-none mt-0.5 shrink-0 w-7">{{ $idx + 1 }}</span>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-red-600 line-clamp-2 leading-snug {{ app()->getLocale() === 'dv' ? 'font-thaana text-right' : '' }}">
                    {{ $trans->title }}
                </p>
                <p class="text-xs text-gray-400 mt-1">{{ number_format($post->views_count) }} {{ __('views') }}</p>
            </div>
        </a>
        @endif
        @endforeach
    </div>
</div>
