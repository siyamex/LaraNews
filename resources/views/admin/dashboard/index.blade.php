@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ now()->format('l, d F Y') }}</p>
        </div>
        <a href="{{ route('admin.posts.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Post
        </a>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Total Posts</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white leading-none mt-0.5">{{ number_format($stats['total_posts']) }}</p>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['published_posts'] }} published</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white leading-none mt-0.5">{{ number_format($stats['total_users']) }}</p>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">+{{ $stats['new_users_today'] }} today</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Active Subs</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white leading-none mt-0.5">{{ number_format($stats['active_subscriptions']) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Premium members</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Revenue (Month)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white leading-none mt-0.5">${{ number_format($stats['revenue_month'], 0) }}</p>
                    <p class="text-xs text-gray-400 mt-1">${{ number_format($stats['revenue_today'], 2) }} today</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Views Today</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white leading-none mt-0.5">{{ number_format($stats['total_views_today']) }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ number_format($stats['total_views_month']) }} this month</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Pending Posts</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white leading-none mt-0.5">{{ number_format($stats['pending_posts']) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Awaiting review</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Pending Comments</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white leading-none mt-0.5">{{ number_format($stats['pending_comments']) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Need moderation</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Views Chart --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-semibold text-gray-900 dark:text-white text-sm">Page Views</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Last 14 days</p>
                </div>
            </div>
            @if($viewsChart->count())
            @php $maxViews = $viewsChart->max('views') ?: 1; @endphp
            <div class="relative h-40">
                {{-- Grid lines --}}
                <div class="absolute inset-x-0 top-0 bottom-6 flex flex-col justify-between pointer-events-none">
                    @foreach(range(1,4) as $i)
                    <div class="border-t border-gray-50 dark:border-gray-700/60 w-full"></div>
                    @endforeach
                </div>
                {{-- Bars --}}
                <div class="absolute inset-x-0 top-0 bottom-6 flex items-end gap-1 px-0.5">
                    @foreach($viewsChart as $day)
                    <div class="flex-1 flex flex-col items-center gap-1 group relative h-full justify-end">
                        <div class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 bg-gray-900 dark:bg-gray-700 text-white text-[10px] px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">
                            {{ number_format($day['views']) }} views
                        </div>
                        <div class="w-full bg-red-500 hover:bg-red-600 rounded-t transition-colors cursor-default"
                             style="height: {{ max(2, ($day['views'] / $maxViews) * 100) }}%"></div>
                    </div>
                    @endforeach
                </div>
                {{-- X-axis labels --}}
                <div class="absolute bottom-0 inset-x-0 flex gap-1 px-0.5">
                    @foreach($viewsChart as $day)
                    <div class="flex-1 text-center">
                        <span class="text-[9px] text-gray-400">{{ \Carbon\Carbon::parse($day['date'])->format('d') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="h-40 flex flex-col items-center justify-center text-gray-300 dark:text-gray-600">
                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <p class="text-sm text-gray-400">No analytics data yet</p>
            </div>
            @endif
        </div>

        {{-- Top Posts --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5">
            <h2 class="font-semibold text-gray-900 dark:text-white text-sm mb-4">Top Posts</h2>
            <ol class="space-y-3.5">
                @forelse($topPosts as $idx => $post)
                @php $t = $post->translation('en') ?? $post->translation('dv'); @endphp
                <li class="flex items-start gap-3">
                    <span class="text-base font-bold text-gray-200 dark:text-gray-700 shrink-0 w-5 leading-snug tabular-nums">{{ $idx + 1 }}</span>
                    <div class="min-w-0">
                        <a href="{{ route('admin.posts.edit', $post) }}"
                           class="text-sm font-medium text-gray-800 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 line-clamp-2 leading-snug transition-colors">
                            {{ $t?->title ?? 'Untitled' }}
                        </a>
                        <p class="text-xs text-gray-400 mt-0.5">{{ number_format($post->views_count) }} views</p>
                    </div>
                </li>
                @empty
                <p class="text-sm text-gray-400 py-4 text-center">No published posts yet.</p>
                @endforelse
            </ol>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent Posts --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="font-semibold text-gray-900 dark:text-white text-sm">Recent Posts</h2>
                <a href="{{ route('admin.posts.index') }}" class="text-xs text-red-600 hover:text-red-700 font-medium transition-colors">View all →</a>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700/50">
                @foreach($recentPosts as $post)
                @php
                    $t = $post->translation('en') ?? $post->translation('dv');
                    $statusVal = $post->status?->value ?? (is_string($post->status) ? $post->status : 'draft');
                    $statusLabel = $post->status?->label() ?? ucfirst($statusVal);
                    $statusClass = match($statusVal) {
                        'published' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                        'review','pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                        'scheduled' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                        'archived' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                        default => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                    };
                @endphp
                <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                         class="w-10 h-8 object-cover rounded-lg shrink-0" alt="">
                    @else
                    <div class="w-10 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg shrink-0 flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('admin.posts.edit', $post) }}"
                           class="text-sm font-medium text-gray-800 dark:text-gray-200 hover:text-red-600 truncate block transition-colors">
                            {{ $t?->title ?? 'Untitled' }}
                        </a>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs px-1.5 py-0.5 rounded-full font-medium {{ $statusClass }}">{{ $statusLabel }}</span>
                            <span class="text-xs text-gray-400">{{ $post->user?->name }}</span>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 shrink-0">{{ $post->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="font-semibold text-gray-900 dark:text-white text-sm">Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-red-600 hover:text-red-700 font-medium transition-colors">View all →</a>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700/50">
                @foreach($recentUsers as $user)
                <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <img src="{{ $user->profile_photo_url }}" class="w-9 h-9 rounded-full object-cover shrink-0" alt="">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                        @if($role = $user->roles->first())
                        <p class="text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', $role->name) }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
