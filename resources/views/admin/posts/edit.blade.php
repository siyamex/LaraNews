@extends('layouts.admin')

@section('title', 'Edit Post')

@section('content')
<div class="mb-5 flex items-center gap-3">
    <a href="{{ route('admin.posts.index') }}" class="text-gray-400 hover:text-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <h1 class="text-xl font-black text-gray-900 dark:text-white">Edit Post</h1>
    @if($post->status === 'published')
    <a href="{{ route('news.show', ['locale' => 'en', 'slug' => $post->translation('en')?->slug ?? $post->translation('dv')?->slug]) }}"
       target="_blank" class="ms-auto text-xs flex items-center gap-1 text-blue-600 hover:text-blue-700">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        View Live
    </a>
    @endif
</div>

<livewire:admin.post-editor :post="$post" />
@endsection
