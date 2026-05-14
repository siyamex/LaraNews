@auth
<div class="relative" x-data="{ open: @entangle('open') }">
    {{-- Bell button --}}
    <button wire:click="toggle"
            class="relative p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($unreadCount > 0)
        <span class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.outside="open = false"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-900 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50"
         style="display:none;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-800">
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Notifications</h3>
            @if($unreadCount > 0)
            <button wire:click="markAllRead" class="text-xs text-red-600 hover:text-red-700 font-medium">
                Mark all read
            </button>
            @endif
        </div>

        {{-- List --}}
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-800">
            @forelse($notifications as $n)
            <div wire:key="{{ $n['id'] }}"
                 wire:click="markRead('{{ $n['id'] }}')"
                 class="flex gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition-colors {{ $n['read'] ? 'opacity-70' : '' }}">
                {{-- Icon --}}
                <div class="shrink-0 w-9 h-9 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mt-0.5">
                    @if($n['type'] === 'new_comment')
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    @elseif($n['type'] === 'new_follower')
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    @else
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @endif
                </div>
                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    @if($n['type'] === 'new_comment')
                        <p class="text-sm text-gray-800 dark:text-gray-200 leading-snug">
                            <span class="font-medium">{{ $n['data']['user_name'] ?? 'Someone' }}</span>
                            commented on <span class="font-medium">{{ str()->limit($n['data']['post_title'] ?? '', 30) }}</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $n['data']['excerpt'] ?? '' }}</p>
                    @elseif($n['type'] === 'new_follower')
                        <p class="text-sm text-gray-800 dark:text-gray-200 leading-snug">
                            <span class="font-medium">{{ $n['data']['follower_name'] ?? 'Someone' }}</span> started following you
                        </p>
                    @else
                        <p class="text-sm text-gray-800 dark:text-gray-200 leading-snug">
                            {{ $n['data']['post_title'] ?? 'New notification' }}
                        </p>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">{{ $n['created_at'] }}</p>
                </div>
                @if(! $n['read'])
                <div class="shrink-0 w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                @endif
            </div>
            @empty
            <div class="px-4 py-8 text-center text-gray-400 text-sm">
                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                No notifications yet
            </div>
            @endforelse
        </div>

        {{-- Footer --}}
        @if(count($notifications) > 0)
        <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
            <a href="{{ route('profile.show') }}" class="text-xs text-red-600 hover:underline font-medium">
                View all notifications →
            </a>
        </div>
        @endif
    </div>
</div>
@endauth
