@extends('layouts.front', ['locale' => $locale])
@section('title', 'Author Dashboard — ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
<div class="container mx-auto px-4">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <img src="{{ auth()->user()->profile_photo_url }}" alt="" class="w-14 h-14 rounded-full object-cover border-2 border-red-500">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(auth()->user()->roles->first()?->name ?? 'Author') }}</p>
        </div>
        <div class="ms-auto flex gap-3">
            <a href="{{ route('admin.posts.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Article
            </a>
            <a href="{{ route('profile.show') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Profile Settings
            </a>
        </div>
    </div>

    <livewire:front.author-dashboard />

</div>
</div>
@endsection
