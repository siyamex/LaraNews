@php $locale = app()->getLocale(); @endphp
<section>
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
            <span class="w-1 h-5 rounded-full shrink-0" style="background-color: {{ $category->color ?? '#DC2626' }}"></span>
            {{ $category->getName($locale) }}
        </h2>
        <a href="{{ route('category.show', ['locale' => $locale, 'slug' => $category->getSlugForLocale($locale)]) }}"
           class="text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium transition-colors flex items-center gap-1">
            {{ $locale === 'dv' ? 'ހުރިހާ ހަބަރު' : 'See all' }}
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- First post: full-width featured --}}
    @if($posts->count())
    @include('components.post-card', ['post' => $posts->first(), 'variant' => 'featured'])
    @endif

    {{-- Remaining posts: 3-col grid --}}
    @if($posts->count() > 1)
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-3">
        @foreach($posts->skip(1)->take(3) as $post)
        @include('components.post-card', ['post' => $post, 'variant' => 'grid'])
        @endforeach
    </div>
    @endif
</section>
