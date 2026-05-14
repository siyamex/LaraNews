@extends('layouts.admin')
@section('title', 'Edit Menu')
@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Menu: {{ $menu->name }}</h1>
    <form action="{{ route('admin.menus.update', $menu) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Menu Name</label>
                <input type="text" name="name" value="{{ old('name', $menu->name) }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                <select name="location" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                    @foreach(['header','footer','sidebar','mobile'] as $loc)
                        <option value="{{ $loc }}" {{ $menu->location === $loc ? 'selected' : '' }}>{{ ucfirst($loc) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Menu Items (JSON)</label>
                <textarea name="items" rows="10"
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm font-mono text-xs">{{ json_encode($menu->items, JSON_PRETTY_PRINT) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Format: [{"label": "Home", "url": "/dv", "target": "_self"}]</p>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Update Menu</button>
            <a href="{{ route('admin.menus.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
