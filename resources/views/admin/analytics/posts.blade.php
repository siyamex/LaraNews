@extends('layouts.admin')
@section('title', 'Post Analytics')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Post Analytics</h1>
    <a href="{{ route('admin.analytics.index') }}" class="text-sm text-red-600 hover:text-red-700">← Back to Analytics</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">#</th>
                <th class="px-4 py-3 text-start">Post</th>
                <th class="px-4 py-3 text-end">Views</th>
                <th class="px-4 py-3 text-center">Published</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($posts as $post)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 text-gray-500">{{ $loop->iteration + ($posts->currentPage() - 1) * $posts->perPage() }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-gray-900 dark:text-white hover:text-red-600 font-medium">
                        {{ $post->translations->where('locale','en')->first()?->title ?? 'Post #'.$post->id }}
                    </a>
                </td>
                <td class="px-4 py-3 text-end font-semibold text-gray-900 dark:text-white">{{ number_format($post->views_count) }}</td>
                <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $post->published_at?->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $posts->links() }}</div>
</div>
@endsection
