@extends('layouts.admin')
@section('title', 'Ad Zones')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ad Zones</h1>
    <a href="{{ route('admin.ad-zones.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">+ New Zone</a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">Name</th>
                <th class="px-4 py-3 text-start">Identifier</th>
                <th class="px-4 py-3 text-start">Size</th>
                <th class="px-4 py-3 text-center">Active Ads</th>
                <th class="px-4 py-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($zones as $zone)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $zone->name }}</td>
                <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $zone->identifier }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $zone->width }}x{{ $zone->height }}</td>
                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $zone->ads_count ?? 0 }}</td>
                <td class="px-4 py-3 text-end">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.ad-zones.edit', $zone) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.ad-zones.destroy', $zone) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No ad zones yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
