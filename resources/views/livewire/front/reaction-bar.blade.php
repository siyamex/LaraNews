<div class="flex items-center gap-1 flex-wrap" x-data="{ open: false }">
    <button @click="open = !open"
            class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-sm">
        @if($userReaction)
            <span>{{ $types[$userReaction] }}</span>
        @else
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        @endif
        <span class="text-gray-600 dark:text-gray-400">{{ array_sum($counts) }}</span>
    </button>

    <div x-show="open" x-transition class="flex items-center gap-1 bg-white dark:bg-gray-800 shadow-lg rounded-full border border-gray-100 dark:border-gray-700 px-3 py-1.5">
        @foreach($types as $type => $emoji)
        <button wire:click="react('{{ $type }}')"
                class="text-lg hover:scale-125 transition-transform {{ $userReaction === $type ? 'scale-125' : '' }}"
                title="{{ ucfirst($type) }}">
            {{ $emoji }}
            @if($counts[$type] > 0)
            <span class="text-xs text-gray-500 -ms-1">{{ $counts[$type] }}</span>
            @endif
        </button>
        @endforeach
    </div>
</div>
