@extends('layouts.admin')
@section('title', 'Edit Campaign')
@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Campaign</h1>
    <form action="{{ route('admin.newsletter.campaigns.update', $campaign) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject</label>
                <input type="text" name="subject" value="{{ old('subject', $campaign->subject) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Content (HTML)</label>
                <textarea name="content" rows="12" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm font-mono text-sm">{{ old('content', $campaign->content) }}</textarea></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Update Campaign</button>
            <a href="{{ route('admin.newsletter.campaigns.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
