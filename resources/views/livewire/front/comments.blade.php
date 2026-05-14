<div class="space-y-6">
    <h2 class="text-xl font-black text-gray-900 dark:text-white">
        {{ __('news.comments') }}
        <span class="text-base font-normal text-gray-400">({{ $post->comments_count }})</span>
    </h2>

    {{-- Comment Form --}}
    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
        @if($replyingTo)
        <div class="flex items-center gap-2 mb-3 text-sm text-blue-600 dark:text-blue-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            {{ __('news.replying_to') }}: <strong>{{ $replyingTo }}</strong>
            <button wire:click="cancelReply" class="ms-2 text-gray-400 hover:text-gray-600">✕</button>
        </div>
        @endif

        @guest
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <input wire:model="guestName" type="text" placeholder="{{ __('news.your_name') }}"
                       class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                @error('guestName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <input wire:model="guestEmail" type="email" placeholder="{{ __('news.your_email') }}"
                       class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                @error('guestEmail') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        @endguest

        <textarea wire:model="content" rows="3" placeholder="{{ __('news.write_comment') }}"
                  class="w-full text-sm rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 resize-none"></textarea>
        @error('content') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

        <div class="flex justify-end mt-3">
            <button wire:click="submit" wire:loading.attr="disabled"
                    class="px-5 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50">
                <span wire:loading.remove>{{ __('news.post_comment') }}</span>
                <span wire:loading>{{ __('news.posting') }}...</span>
            </button>
        </div>
    </div>

    {{-- Comments List --}}
    <div class="space-y-4">
        @forelse($comments as $comment)
        <div class="flex gap-4">
            <img src="{{ $comment->user?->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->guest_name ?? 'A') . '&background=DC2626&color=fff' }}"
                 alt="" class="w-10 h-10 rounded-full object-cover shrink-0">
            <div class="flex-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="font-semibold text-sm text-gray-900 dark:text-white">
                            {{ $comment->user?->name ?? $comment->guest_name }}
                        </span>
                        @if($comment->user?->is_verified_journalist)
                        <span class="text-blue-500" title="Verified Journalist">✓</span>
                        @endif
                        <span class="text-xs text-gray-400 ms-auto">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                </div>
                <button wire:click="replyTo({{ $comment->id }}, '{{ $comment->user?->name ?? $comment->guest_name }}')"
                        class="mt-1 text-xs text-gray-400 hover:text-red-600 transition-colors ms-2">
                    {{ __('news.reply') }}
                </button>

                {{-- Nested replies --}}
                @if($comment->replies->count())
                <div class="mt-3 ms-4 space-y-3">
                    @foreach($comment->replies as $reply)
                    <div class="flex gap-3">
                        <img src="{{ $reply->user?->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($reply->guest_name ?? 'A') . '&background=3B82F6&color=fff' }}"
                             alt="" class="w-8 h-8 rounded-full object-cover shrink-0">
                        <div class="bg-gray-50 dark:bg-gray-800/80 rounded-xl p-3 border border-gray-100 dark:border-gray-700 flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-xs text-gray-900 dark:text-white">{{ $reply->user?->name ?? $reply->guest_name }}</span>
                                <span class="text-xs text-gray-400 ms-auto">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $reply->content }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @empty
        <p class="text-center text-gray-400 py-8">{{ __('news.no_comments') }}</p>
        @endforelse
    </div>

    {{ $comments->links() }}
</div>
