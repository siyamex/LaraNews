@extends('layouts.admin')
@section('title', 'Post Revisions')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Revisions</h1>
        <p class="text-sm text-gray-500 mt-1">Post #{{ $post->id }}</p>
    </div>
    <a href="{{ route('admin.posts.edit', $post) }}" class="text-sm text-red-600 hover:text-red-700">← Back to Post</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">#</th>
                <th class="px-4 py-3 text-start">Editor</th>
                <th class="px-4 py-3 text-start">Changes</th>
                <th class="px-4 py-3 text-center">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($revisions as $revision)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 text-gray-500 text-xs">v{{ $revision->id }}</td>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-900 dark:text-white text-xs">{{ $revision->user?->name ?? 'System' }}</p>
                </td>
                <td class="px-4 py-3">
                    @php $payload = $revision->payload ?? []; @endphp
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                        Status: {{ $payload['status'] ?? '-' }},
                        Title: {{ Str::limit($payload['title'] ?? '-', 40) }}
                    </p>
                </td>
                <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $revision->created_at->format('M d, Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-12 text-center text-gray-400">No revisions found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $revisions->links() }}</div>
</div>
@endsection
