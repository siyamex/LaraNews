@extends('layouts.admin')
@section('title', 'Edit Poll')
@section('content')
<div class="max-w-xl">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Poll</h1>
    <form action="{{ route('admin.polls.update', $poll) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Question (EN)</label>
                <input type="text" name="question" value="{{ old('question', $poll->question) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required></div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Options</label>
                @foreach($poll->options as $idx => $option)
                <div class="flex gap-2 mb-2">
                    <input type="text" name="options[{{ $idx }}]" value="{{ $option->text }}"
                           class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm text-sm">
                    <span class="text-xs text-gray-500 flex items-center px-2">{{ $option->votes_count }} votes</span>
                </div>
                @endforeach
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $poll->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-red-600">
                <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
            </label>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Update Poll</button>
            <a href="{{ route('admin.polls.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
