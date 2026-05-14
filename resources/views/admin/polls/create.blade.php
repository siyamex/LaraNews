@extends('layouts.admin')
@section('title', 'New Poll')
@section('content')
<div class="max-w-xl" x-data="{ options: ['', '', '', ''] }">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">New Poll</h1>
    <form action="{{ route('admin.polls.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Question (EN)</label>
                <input type="text" name="question" value="{{ old('question') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Question (DV)</label>
                <input type="text" name="question_dv" value="{{ old('question_dv') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" dir="rtl"></div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Options</label>
                <template x-for="(opt, idx) in options" :key="idx">
                    <div class="flex gap-2 mb-2">
                        <input type="text" :name="'options['+idx+']'" x-model="options[idx]"
                               :placeholder="'Option '+(idx+1)"
                               class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm text-sm">
                        <button type="button" @click="options.splice(idx,1)" class="text-red-600 hover:text-red-800 px-2">✕</button>
                    </div>
                </template>
                <button type="button" @click="options.push('')" class="text-sm text-red-600 hover:text-red-700">+ Add Option</button>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expires At</label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm"></div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-red-600">
                <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
            </label>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Create Poll</button>
            <a href="{{ route('admin.polls.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
