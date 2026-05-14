@extends('layouts.app')

@section('title', $author->name . ' — ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Author Profile --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 mb-8">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <img src="{{ $author->profile_photo_url }}" alt="{{ $author->name }}"
                 class="w-24 h-24 rounded-full object-cover ring-4 ring-red-100 dark:ring-red-900/30">
            <div class="text-center sm:text-start">
                <div class="flex items-center gap-2 justify-center sm:justify-start">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $author->name }}</h1>
                    @if($author->is_verified_journalist)
                        <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            ✓ {{ __('Journalist') }}
                        </span>
                    @endif
                </div>
                @if($author->username)
                    <p class="text-gray-500 text-sm">@{{ $author->username }}</p>
                @endif
                @if($author->bio)
                    <p class="mt-3 text-gray-600 dark:text-gray-400 max-w-lg">{{ $author->bio }}</p>
                @endif
                <div class="flex items-center gap-6 mt-4 text-sm text-gray-500">
                    <span>{{ number_format($author->posts_count) }} {{ __('articles') }}</span>
                    <span>{{ number_format($author->followers_count) }} {{ __('followers') }}</span>
                </div>
                @auth
                    @if(auth()->id() !== $author->id)
                    <form action="{{ route('follow.toggle', ['locale' => app()->getLocale(), 'user' => $author]) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                            {{ auth()->user()->following->contains($author) ? __('Unfollow') : __('Follow') }}
                        </button>
                    </form>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    {{-- Author's Articles --}}
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Articles by') }} {{ $author->name }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
            <x-post-card :post="$post" />
        @empty
            <div class="col-span-3 py-12 text-center text-gray-400">
                {{ __('No articles published yet.') }}
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $posts->links() }}</div>
</div>
@endsection
