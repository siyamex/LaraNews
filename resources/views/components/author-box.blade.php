@php $locale = app()->getLocale(); @endphp
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5">
    <h3 class="font-black text-gray-900 dark:text-white mb-4 text-sm uppercase tracking-wide opacity-60">
        {{ $locale === 'dv' ? 'ލިޔުންތެރިޔާ' : 'About the Author' }}
    </h3>
    <div class="flex gap-4">
        <a href="{{ route('author.show', ['locale' => $locale, 'username' => $user->username ?? $user->id]) }}">
            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                 class="w-16 h-16 rounded-full object-cover ring-2 ring-red-100 dark:ring-red-900">
        </a>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <a href="{{ route('author.show', ['locale' => $locale, 'username' => $user->username ?? $user->id]) }}"
                   class="font-black text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                    {{ $user->name }}
                </a>
                @if($user->is_verified_journalist)
                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                @endif
            </div>
            @if($user->bio)
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-3">{{ $user->bio }}</p>
            @endif
            <div class="flex items-center gap-3 mt-2">
                @if($user->twitter_handle)
                <a href="https://twitter.com/{{ $user->twitter_handle }}" target="_blank" rel="noopener"
                   class="text-gray-400 hover:text-black dark:hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                @endif
                <span class="text-xs text-gray-400">{{ $user->posts_count }} {{ $locale === 'dv' ? 'ހަބަރު' : 'articles' }}</span>
            </div>
        </div>
    </div>
</div>
