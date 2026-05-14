@extends('layouts.admin')
@section('title', 'Edit Newsletter List')
@section('content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit List: {{ $list->name }}</h1>
    <form action="{{ route('admin.newsletter.lists.update', $list) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">List Name</label>
                <input type="text" name="name" value="{{ old('name', $list->name) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">{{ old('description', $list->description) }}</textarea></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Update List</button>
            <a href="{{ route('admin.newsletter.lists.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
