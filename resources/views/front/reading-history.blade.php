@extends('layouts.app')

@section('title', __('Reading History') . ' — ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">{{ __('Reading History') }}</h1>

    <div class="space-y-4">
        @forelse($history as $item)
        @php $post = $item->post; $trans = $post?->translations->where('locale', app()->getLocale())->first(); @endphp
        @if($post && $trans)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 flex items-center gap-4">
            @if($post->featured_image)
                <img src="{{ Storage::url($post->featured_image) }}" alt=""
                     class="w-16 h-16 rounded-lg object-cover shrink-0">
            @endif
            <div class="flex-1 min-w-0">
                <a href="{{ route('news.show', ['locale' => app()->getLocale(), 'slug' => $trans->slug]) }}"
                   class="font-semibold text-gray-900 dark:text-white hover:text-red-600 line-clamp-1">
                    {{ $trans->title }}
                </a>
                <p class="text-xs text-gray-500 mt-1">{{ $item->created_at->diffForHumans() }}</p>
            </div>
            <div class="shrink-0 text-right">
                <div class="text-xs text-gray-500">{{ $item->read_percentage }}%</div>
                <div class="w-16 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full mt-1">
                    <div class="h-full bg-red-500 rounded-full" style="width: {{ $item->read_percentage }}%"></div>
                </div>
            </div>
        </div>
        @endif
        @empty
        <div class="py-16 text-center">
            <p class="text-gray-400 text-lg">{{ __('No reading history yet.') }}</p>
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="mt-4 inline-block text-red-600 hover:text-red-700 text-sm font-medium">
                {{ __('Start reading') }} →
            </a>
        </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $history->links() }}</div>
</div>
@endsection
