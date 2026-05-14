@extends('layouts.app')

@section('title', __('Bookmarks') . ' — ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">{{ __('My Bookmarks') }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($bookmarks as $post)
            <x-post-card :post="$post" />
        @empty
            <div class="col-span-3 py-16 text-center">
                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
                <p class="text-gray-400 text-lg">{{ __('No bookmarks yet.') }}</p>
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="mt-4 inline-block text-red-600 hover:text-red-700 text-sm font-medium">
                    {{ __('Explore articles') }} →
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $bookmarks->links() }}</div>
</div>
@endsection
