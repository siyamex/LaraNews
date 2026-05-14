@extends('layouts.admin')
@section('title', 'Analytics')
@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-700 dark:text-gray-300">Analytics</span>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Analytics</h1>
    <div class="flex items-center gap-2">
        @foreach(['7'=>'7 days','30'=>'30 days','90'=>'90 days'] as $p => $label)
        <a href="{{ route('admin.analytics.index', ['period'=>$p]) }}"
           class="px-3 py-1.5 text-xs font-medium rounded-lg {{ $period == $p ? 'bg-red-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>
</div>

{{-- Views Chart --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Page Views</h2>
    <div class="overflow-x-auto">
        <div class="flex items-end gap-1 h-40">
            @php $maxViews = $viewsChart->max('views') ?: 1; @endphp
            @foreach($viewsChart as $day)
            <div class="flex-1 flex flex-col items-center gap-1 group">
                <div class="w-full bg-red-500 rounded-t opacity-80 hover:opacity-100 transition-opacity relative"
                     style="height: {{ ($day->views / $maxViews) * 100 }}%"
                     title="{{ $day->date }}: {{ number_format($day->views) }} views">
                </div>
                <span class="text-xs text-gray-400 rotate-45 origin-left hidden group-hover:block">{{ $day->date }}</span>
            </div>
            @endforeach
        </div>
        <p class="text-xs text-gray-400 mt-2">{{ $viewsChart->sum('views') }} total views in last {{ $period }} days</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Device Stats --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Devices</h2>
        @php $totalDevices = $deviceStats->sum('count') ?: 1; @endphp
        <div class="space-y-3">
            @foreach($deviceStats as $device)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600 dark:text-gray-400 capitalize">{{ $device->device_type ?? 'Unknown' }}</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ round($device->count / $totalDevices * 100) }}%</span>
                </div>
                <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-red-500 rounded-full" style="width: {{ $device->count / $totalDevices * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Country Stats --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Countries</h2>
        <div class="space-y-2">
            @foreach($countryStats as $country)
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 dark:text-gray-400">{{ $country->country_code ?: 'Unknown' }}</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ number_format($country->count) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Referrer Stats --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Referrers</h2>
        <div class="space-y-2">
            @foreach($refererStats as $ref)
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600 dark:text-gray-400 truncate max-w-[150px]" title="{{ $ref->referer }}">
                    {{ parse_url($ref->referer, PHP_URL_HOST) ?: $ref->referer }}
                </span>
                <span class="font-medium text-gray-900 dark:text-white">{{ number_format($ref->count) }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Top Posts --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Top Posts</h2>
        <a href="{{ route('admin.analytics.posts') }}" class="text-sm text-red-600 hover:text-red-700">View all</a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">Post</th>
                <th class="px-4 py-3 text-end">Views</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($topPosts as $post)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3">
                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-gray-900 dark:text-white hover:text-red-600 font-medium">
                        {{ $post->translations->where('locale','en')->first()?->title ?? 'Post #'.$post->id }}
                    </a>
                </td>
                <td class="px-4 py-3 text-end text-gray-600 dark:text-gray-400">{{ number_format($post->views_count) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
