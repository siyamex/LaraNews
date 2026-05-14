@extends('layouts.admin')
@section('title', 'Edit Category')
@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <a href="{{ route('admin.categories.index') }}" class="hover:text-gray-700">Categories</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-700 dark:text-gray-300">Edit</span>
@endsection

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Category</h1>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')
        @php
            $dvTrans = $category->translations->where('locale','dv')->first();
            $enTrans = $category->translations->where('locale','en')->first();
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">English Name</label>
                    <input type="text" name="translations[en][name]"
                           value="{{ old('translations.en.name', $enTrans?->name) }}"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dhivehi Name</label>
                    <input type="text" name="translations[dv][name]"
                           value="{{ old('translations.dv.name', $dvTrans?->name) }}"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">English Slug</label>
                    <input type="text" name="translations[en][slug]"
                           value="{{ old('translations.en.slug', $enTrans?->slug) }}"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm font-mono text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dhivehi Slug</label>
                    <input type="text" name="translations[dv][slug]"
                           value="{{ old('translations.dv.slug', $dvTrans?->slug) }}"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm font-mono text-sm">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Icon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon) }}"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm text-2xl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Color</label>
                    <input type="color" name="color" value="{{ old('color', $category->color ?? '#DC2626') }}"
                           class="w-full h-10 rounded-lg border-gray-300 cursor-pointer">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
                    <input type="number" name="order" value="{{ old('order', $category->order) }}"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                </div>
            </div>

            <div class="flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}
                           class="rounded border-gray-300 text-red-600">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ $category->is_featured ? 'checked' : '' }}
                           class="rounded border-gray-300 text-red-600">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Featured</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="show_in_menu" value="1" {{ $category->show_in_menu ? 'checked' : '' }}
                           class="rounded border-gray-300 text-red-600">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Show in Menu</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
