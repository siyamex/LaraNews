@extends('layouts.admin')

@section('title', 'Create Post')

@section('content')
<div class="mb-5 flex items-center gap-3">
    <a href="{{ route('admin.posts.index') }}" class="text-gray-400 hover:text-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <h1 class="text-xl font-black text-gray-900 dark:text-white">Create Post</h1>
</div>

<livewire:admin.post-editor />
@endsection
