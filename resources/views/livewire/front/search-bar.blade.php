<div class="relative" x-data="{ focused: false }" @click.outside="focused = false">
    <div class="relative">
        <input wire:model.live.debounce.300ms="query"
               @focus="focused = true"
               type="search"
               placeholder="{{ app()->getLocale() === 'dv' ? 'ހޯދާ...' : 'Search...' }}"
               class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
        <svg class="absolute start-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        @if($query)
        <button wire:click="clear" class="absolute end-3 top-2.5 text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        @endif
    </div>

    @if($open && count($results) > 0)
    <div class="absolute top-full start-0 end-0 mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50 animate-fade-in">
        @foreach($results as $result)
        <a href="{{ $result['url'] }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            @if($result['image'])
            <img src="{{ $result['image'] }}" alt="" class="w-12 h-9 object-cover rounded-lg shrink-0">
            @endif
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $result['title'] }}</p>
                @if($result['category'])
                <p class="text-xs text-red-600 dark:text-red-400">{{ $result['category'] }}</p>
                @endif
            </div>
        </a>
        @endforeach
        <a href="{{ route('search', ['locale' => app()->getLocale(), 'q' => $query]) }}"
           class="flex items-center justify-center py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 font-medium border-t border-gray-100 dark:border-gray-700">
            {{ app()->getLocale() === 'dv' ? 'ހުރިހާ ނަތީޖާ ދެކްވ' : 'View all results' }} →
        </a>
    </div>
    @endif
</div>
