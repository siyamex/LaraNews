@extends('layouts.admin')
@section('title', 'Edit Ad')
@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Ad: {{ $ad->name }}</h1>
    <form action="{{ route('admin.ads.update', $ad) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ad Name</label>
                <input type="text" name="name" value="{{ old('name', $ad->name) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Zone</label>
                <select name="ad_zone_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                    <option value="">-- No Zone --</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}" {{ $ad->ad_zone_id == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                    @endforeach
                </select></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code / URL</label>
                <textarea name="code" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm font-mono text-sm">{{ old('code', $ad->code) }}</textarea></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target URL</label>
                <input type="url" name="url" value="{{ old('url', $ad->url) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm"></div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $ad->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-red-600">
                <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
            </label>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Update Ad</button>
            <a href="{{ route('admin.ads.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
