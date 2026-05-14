@extends('layouts.app')

@section('title', ($tag->translations->where('locale', app()->getLocale())->first()?->name ?? $tag->slug) . ' — ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            # {{ $tag->translations->where('locale', app()->getLocale())->first()?->name ?? $tag->slug }}
        </h1>
        <p class="mt-2 text-gray-500">{{ number_format($tag->posts_count) }} {{ __('articles') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
            <x-post-card :post="$post" />
        @empty
            <div class="col-span-3 py-16 text-center text-gray-400">
                <p>{{ __('No articles found for this tag.') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</div>
@endsection
