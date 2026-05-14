@extends('layouts.admin')
@section('title', 'Newsletter Lists')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Newsletter Lists</h1>
    <a href="{{ route('admin.newsletter.lists.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">+ New List</a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">Name</th>
                <th class="px-4 py-3 text-start">Slug</th>
                <th class="px-4 py-3 text-center">Subscribers</th>
                <th class="px-4 py-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($lists as $list)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $list->name }}</td>
                <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $list->slug }}</td>
                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ number_format($list->subscribers_count) }}</td>
                <td class="px-4 py-3 text-end">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.newsletter.lists.edit', $list) }}" class="text-blue-600 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.newsletter.lists.destroy', $list) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-xs font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-12 text-center text-gray-400">No lists yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
