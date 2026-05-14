@extends('layouts.admin')
@section('title', 'New Page')
@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">New Page</h1>
    <form action="{{ route('admin.pages.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm font-mono text-sm" required>
            </div>

            @foreach(['en'=>'English','dv'=>'Dhivehi'] as $locale => $lang)
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ $lang }}</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                        <input type="text" name="translations[{{ $locale }}][title]" value="{{ old("translations.$locale.title") }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm"
                               {{ $locale === 'dv' ? 'dir=rtl' : '' }}>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content</label>
                        <textarea name="translations[{{ $locale }}][content]" rows="8"
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm"
                                  {{ $locale === 'dv' ? 'dir=rtl' : '' }}>{{ old("translations.$locale.content") }}</textarea>
                    </div>
                </div>
            </div>
            @endforeach

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-red-600">
                <span class="text-sm text-gray-700 dark:text-gray-300">Published</span>
            </label>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Save Page</button>
            <a href="{{ route('admin.pages.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
