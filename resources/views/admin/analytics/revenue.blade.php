@extends('layouts.admin')
@section('title', 'Revenue Analytics')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Revenue Analytics</h1>
    <a href="{{ route('admin.analytics.index') }}" class="text-sm text-red-600 hover:text-red-700">← Back to Analytics</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Monthly Revenue (MVR)</h2>
    @php $maxRevenue = $revenueChart->max('revenue') ?: 1; $total = $revenueChart->sum('revenue'); @endphp
    <p class="text-3xl font-bold text-gray-900 dark:text-white mb-6">MVR {{ number_format($total, 2) }} <span class="text-sm font-normal text-gray-500">last 12 months</span></p>
    <div class="flex items-end gap-2 h-48">
        @foreach($revenueChart as $month)
        <div class="flex-1 flex flex-col items-center gap-1">
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ number_format($month['revenue']) }}</span>
            <div class="w-full bg-red-500 rounded-t"
                 style="height: {{ ($maxRevenue > 0 ? $month['revenue'] / $maxRevenue * 100 : 0) }}%; min-height: 4px;"
                 title="{{ $month['month'] }}: MVR {{ number_format($month['revenue'], 2) }}">
            </div>
            <span class="text-xs text-gray-400 text-center">{{ $month['month'] }}</span>
        </div>
        @endforeach
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">Month</th>
                <th class="px-4 py-3 text-end">Revenue (MVR)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($revenueChart->sortByDesc('month') as $month)
            <tr>
                <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $month['month'] }}</td>
                <td class="px-4 py-3 text-end font-semibold text-gray-900 dark:text-white">{{ number_format($month['revenue'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
