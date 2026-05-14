@extends('layouts.admin')
@section('title', 'Comments')
@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-700 dark:text-gray-300">Comments</span>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Comments</h1>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400">
            <tr>
                <th class="px-4 py-3 text-start">Author</th>
                <th class="px-4 py-3 text-start">Comment</th>
                <th class="px-4 py-3 text-start">Post</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-center">Date</th>
                <th class="px-4 py-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($comments as $comment)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-900 dark:text-white text-xs">{{ $comment->guest_name ?? $comment->user?->name }}</p>
                    <p class="text-xs text-gray-500">{{ $comment->guest_email ?? $comment->user?->email }}</p>
                </td>
                <td class="px-4 py-3 max-w-xs">
                    <p class="text-gray-700 dark:text-gray-300 line-clamp-2 text-xs">{{ $comment->body }}</p>
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.posts.edit', $comment->post_id) }}" class="text-blue-600 hover:underline text-xs">
                        Post #{{ $comment->post_id }}
                    </a>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        {{ $comment->status === 'approved' ? 'bg-green-100 text-green-800' :
                          ($comment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($comment->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</td>
                <td class="px-4 py-3 text-end">
                    <div class="flex items-center justify-end gap-2">
                        @if($comment->status !== 'approved')
                        <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 text-xs font-medium">Approve</button>
                        </form>
                        @endif
                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                              onsubmit="return confirm('Delete this comment?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">No comments found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $comments->links() }}</div>
</div>
@endsection
