@extends('layouts.admin')
@section('title', 'New Campaign')
@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">New Email Campaign</h1>
    <form action="{{ route('admin.newsletter.campaigns.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Send To List</label>
                <select name="list_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                    <option value="">-- All Subscribers --</option>
                    @foreach($lists as $list)
                        <option value="{{ $list->id }}" {{ old('list_id') == $list->id ? 'selected' : '' }}>{{ $list->name }} ({{ $list->subscribers_count }})</option>
                    @endforeach
                </select></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Content (HTML)</label>
                <textarea name="content" rows="12" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm font-mono text-sm">{{ old('content') }}</textarea></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Schedule At (optional)</label>
                <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm"></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" name="send_now" value="1" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Send Now</button>
            <button type="submit" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Save as Draft</button>
            <a href="{{ route('admin.newsletter.campaigns.index') }}" class="px-6 py-2.5 text-gray-500 hover:text-gray-700 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
