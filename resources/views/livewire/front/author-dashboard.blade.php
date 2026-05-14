<div>
    {{-- Stats Row --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        @foreach([
            ['label' => 'Total Posts',   'value' => $stats['posts'],       'icon' => 'document-text', 'color' => 'blue'],
            ['label' => 'Published',     'value' => $stats['published'],   'icon' => 'check-circle',  'color' => 'green'],
            ['label' => 'Drafts',        'value' => $stats['draft'],       'icon' => 'pencil',        'color' => 'yellow'],
            ['label' => 'Total Views',   'value' => number_format($stats['total_views']), 'icon' => 'eye', 'color' => 'purple'],
            ['label' => 'This Month',    'value' => $stats['this_month'],  'icon' => 'calendar',      'color' => 'red'],
            ['label' => 'Followers',     'value' => $stats['followers'],   'icon' => 'users',         'color' => 'pink'],
        ] as $s)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $s['label'] }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $s['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 bg-gray-100 dark:bg-gray-800 p-1 rounded-xl mb-6 w-fit">
        @foreach(['posts' => 'My Posts', 'analytics' => 'Top Posts'] as $tab => $label)
        <button wire:click="switchTab('{{ $tab }}')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                       {{ $activeTab === $tab ? 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- Posts Tab --}}
    @if($activeTab === 'posts')
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">

        {{-- Filters --}}
        <div class="flex flex-col sm:flex-row gap-3 p-4 border-b border-gray-100 dark:border-gray-700">
            <input wire:model.live.debounce.300ms="search"
                   type="search" placeholder="Search posts..."
                   class="flex-1 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2 focus:ring-2 focus:ring-red-500">
            <select wire:model.live="statusFilter"
                    class="rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2">
                <option value="">All Status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="pending">Pending Review</option>
                <option value="scheduled">Scheduled</option>
            </select>
            <a href="{{ route('admin.posts.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Post
            </a>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Status</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Views</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Date</th>
                        <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($posts as $post)
                    @php $t = $post->translations->first(); @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="" class="w-10 h-10 object-cover rounded-lg shrink-0">
                                @endif
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 dark:text-white truncate max-w-xs">{{ $t?->title ?? 'Untitled' }}</p>
                                    <p class="text-xs text-gray-400">{{ $post->category?->translation($locale)?->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            @php $colors = ['published' => 'green', 'draft' => 'gray', 'pending' => 'yellow', 'scheduled' => 'blue', 'archived' => 'red']; $c = $colors[$post->status] ?? 'gray'; @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $c }}-100 text-{{ $c }}-700 dark:bg-{{ $c }}-900/30 dark:text-{{ $c }}-400">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 hidden md:table-cell">{{ number_format($post->views_count) }}</td>
                        <td class="px-4 py-3 text-gray-400 text-xs hidden lg:table-cell">{{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="text-xs text-red-600 hover:text-red-700 font-medium">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-400 text-sm">No posts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
    @endif

    {{-- Analytics Tab --}}
    @if($activeTab === 'analytics')
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Top Performing Posts</h3>
        <div class="space-y-3">
            @forelse($topPosts as $i => $post)
            @php $t = $post->translations->first(); @endphp
            <div class="flex items-center gap-4">
                <span class="text-2xl font-bold text-gray-200 dark:text-gray-700 w-8 shrink-0">#{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ $t?->title ?? 'Untitled' }}</p>
                    <div class="flex items-center gap-3 mt-1">
                        <div class="flex-1 bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                            @php $pct = $topPosts->first()?->views_count > 0 ? ($post->views_count / $topPosts->first()->views_count) * 100 : 0; @endphp
                            <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs text-gray-500 shrink-0">{{ number_format($post->views_count) }} views</span>
                    </div>
                </div>
                <a href="{{ $t ? route('news.show', ['locale' => $locale, 'slug' => $t->slug]) : '#' }}" class="text-xs text-red-600 hover:underline shrink-0">View →</a>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">No published posts yet.</p>
            @endforelse
        </div>
    </div>
    @endif
</div>
