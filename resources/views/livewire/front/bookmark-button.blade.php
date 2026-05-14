<button wire:click="toggle"
        class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm transition-all {{ $bookmarked ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:text-amber-600' }}"
        title="{{ $bookmarked ? __('news.remove_bookmark') : __('news.bookmark') }}">
    <svg class="w-4 h-4" fill="{{ $bookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
    </svg>
    <span class="hidden sm:inline">{{ $bookmarked ? __('news.saved') : __('news.bookmark') }}</span>
</button>
