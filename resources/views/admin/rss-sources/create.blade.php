@extends('layouts.admin')
@section('title', 'New RSS Source')
@section('content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">New RSS Source</h1>
    <form action="{{ route('admin.rss-sources.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Source Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">RSS Feed URL</label>
                <input type="url" name="url" value="{{ old('url') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Category</label>
                <select name="category_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                    <option value="">-- No Category --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->translations->where('locale','en')->first()?->name ?? $cat->slug }}
                        </option>
                    @endforeach
                </select></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Locale</label>
                <select name="locale" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                    <option value="en">English</option>
                    <option value="dv">Dhivehi</option>
                </select></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Import every (hours)</label>
                <input type="number" name="import_interval" value="{{ old('import_interval', 6) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm"></div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-red-600">
                <span class="text-sm text-gray-700 dark:text-gray-300">Auto Import Enabled</span>
            </label>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Add Source</button>
            <a href="{{ route('admin.rss-sources.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
