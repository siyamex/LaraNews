@extends('layouts.admin')
@section('title', 'Edit Ad Zone')
@section('content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Ad Zone: {{ $adZone->name }}</h1>
    <form action="{{ route('admin.ad-zones.update', $adZone) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $adZone->name) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Identifier</label>
                <input type="text" name="identifier" value="{{ old('identifier', $adZone->identifier) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm font-mono text-sm"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Width (px)</label>
                    <input type="number" name="width" value="{{ old('width', $adZone->width) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Height (px)</label>
                    <input type="number" name="height" value="{{ old('height', $adZone->height) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm"></div>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Update Zone</button>
            <a href="{{ route('admin.ad-zones.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
