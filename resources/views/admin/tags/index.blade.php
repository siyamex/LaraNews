@extends('layouts.admin')
@section('title', 'Tags')
@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-700 dark:text-gray-300">Tags</span>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tags</h1>
    <a href="{{ route('admin.tags.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">+ New Tag</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400">
            <tr>
                <th class="px-4 py-3 text-start">Name</th>
                <th class="px-4 py-3 text-start">Dhivehi</th>
                <th class="px-4 py-3 text-center">Posts</th>
                <th class="px-4 py-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($tags as $tag)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                    {{ $tag->translations->where('locale','en')->first()?->name ?? $tag->slug }}
                </td>
                <td class="px-4 py-3 text-gray-500" dir="rtl">
                    {{ $tag->translations->where('locale','dv')->first()?->name }}
                </td>
                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ number_format($tag->posts_count) }}</td>
                <td class="px-4 py-3 text-end">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST"
                              onsubmit="return confirm('Delete this tag?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-12 text-center text-gray-400">No tags yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $tags->links() }}</div>
</div>
@endsection
