@extends('layouts.admin')
@section('title', 'Polls')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Polls</h1>
    <a href="{{ route('admin.polls.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">+ New Poll</a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">Question</th>
                <th class="px-4 py-3 text-center">Votes</th>
                <th class="px-4 py-3 text-center">Active</th>
                <th class="px-4 py-3 text-center">Expires</th>
                <th class="px-4 py-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($polls as $poll)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white max-w-xs truncate">{{ $poll->question }}</td>
                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ number_format($poll->votes_count) }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $poll->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ $poll->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $poll->expires_at?->format('M d, Y') ?? '∞' }}</td>
                <td class="px-4 py-3 text-end">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.polls.edit', $poll) }}" class="text-blue-600 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.polls.destroy', $poll) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-xs font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No polls yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $polls->links() }}</div>
</div>
@endsection
