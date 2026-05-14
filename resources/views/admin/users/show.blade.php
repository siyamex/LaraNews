@extends('layouts.admin')
@section('title', 'User Profile')
@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-4 mb-6">
            <img src="{{ $user->profile_photo_url }}" alt="" class="w-16 h-16 rounded-full object-cover">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                    {{ $user->roles->first()?->name ?? 'subscriber' }}
                </span>
            </div>
        </div>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div><dt class="text-gray-500">Username</dt><dd class="font-medium dark:text-white">@{{ $user->username }}</dd></div>
            <div><dt class="text-gray-500">Locale</dt><dd class="font-medium dark:text-white">{{ $user->locale }}</dd></div>
            <div><dt class="text-gray-500">Posts</dt><dd class="font-medium dark:text-white">{{ number_format($user->posts_count) }}</dd></div>
            <div><dt class="text-gray-500">Joined</dt><dd class="font-medium dark:text-white">{{ $user->created_at->format('M d, Y') }}</dd></div>
        </dl>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">Edit User</a>
    </div>
</div>
@endsection
