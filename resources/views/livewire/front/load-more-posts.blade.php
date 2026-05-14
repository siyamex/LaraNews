<div>
    <div class="space-y-4">
        @foreach($posts as $post)
        @include('components.post-card', ['post' => $post, 'variant' => 'list'])
        @endforeach
    </div>

    @if($hasMore)
    <div class="mt-6 text-center">
        <button wire:click="loadMore" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-semibold rounded-xl hover:bg-gray-700 dark:hover:bg-gray-100 transition-colors disabled:opacity-50">
            <span wire:loading.remove>{{ app()->getLocale() === 'dv' ? 'އިތުރު ހަބަރު' : 'Load More' }}</span>
            <span wire:loading class="flex items-center gap-2">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                {{ app()->getLocale() === 'dv' ? 'ލޯޑު ވަނީ...' : 'Loading...' }}
            </span>
        </button>
    </div>
    @endif
</div>
