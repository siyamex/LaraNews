@extends('layouts.admin')
@section('title', 'Quizzes')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Quizzes</h1>
    <a href="{{ route('admin.quizzes.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">+ New Quiz</a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">Title</th>
                <th class="px-4 py-3 text-center">Questions</th>
                <th class="px-4 py-3 text-center">Plays</th>
                <th class="px-4 py-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($quizzes as $quiz)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $quiz->title }}</td>
                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $quiz->questions_count ?? 0 }}</td>
                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ number_format($quiz->plays_count ?? 0) }}</td>
                <td class="px-4 py-3 text-end">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="text-blue-600 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-xs font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-12 text-center text-gray-400">No quizzes yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $quizzes->links() }}</div>
</div>
@endsection
