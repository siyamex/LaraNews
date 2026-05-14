@extends('layouts.admin')
@section('title', 'Categories')
@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-700 dark:text-gray-300">Categories</span>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn-primary">+ New Category</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400">
            <tr>
                <th class="px-4 py-3 text-start">#</th>
                <th class="px-4 py-3 text-start">Name</th>
                <th class="px-4 py-3 text-start">Slug</th>
                <th class="px-4 py-3 text-center">Posts</th>
                <th class="px-4 py-3 text-center">Active</th>
                <th class="px-4 py-3 text-center">Featured</th>
                <th class="px-4 py-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($categories as $category)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 text-gray-500">{{ $category->id }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        @if($category->icon)
                            <span class="text-xl">{{ $category->icon }}</span>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $category->translations->where('locale', 'en')->first()?->name ?? $category->slug }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $category->translations->where('locale', 'dv')->first()?->name }}
                            </p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $category->slug }}</td>
                <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ number_format($category->posts_count) }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ $category->is_active ? 'Yes' : 'No' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $category->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ $category->is_featured ? 'Yes' : 'No' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-end">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-12 text-center text-gray-400">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $categories->links() }}</div>
</div>
@endsection
