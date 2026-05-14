@extends('layouts.app')

@section('content')
@php $locale = app()->getLocale(); @endphp

<div class="container mx-auto px-4 py-8 max-w-5xl">
    {{-- Search Form --}}
    <form action="{{ route('search', ['locale' => $locale]) }}" method="GET" class="mb-8">
        <div class="relative">
            <input name="q" value="{{ $query }}" type="search"
                   class="w-full pl-14 pr-6 py-4 text-lg border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-2xl focus:ring-2 focus:ring-red-500 focus:border-red-500"
                   placeholder="{{ $locale === 'dv' ? 'ހޯދާ...' : 'Search stories...' }}"
                   autofocus>
            <button type="submit" class="absolute start-4 top-4">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </div>
    </form>

    @if($query)
    <div class="flex items-center gap-2 mb-6">
        <h1 class="text-xl font-black text-gray-900 dark:text-white">
            {{ $locale === 'dv' ? 'ހޯދިފައި' : 'Results for' }}: <span class="text-red-600">{{ $query }}</span>
        </h1>
        @if($posts->total())
        <span class="text-sm text-gray-400">({{ $posts->total() }} {{ $locale === 'dv' ? 'ނަތީޖާ' : 'results' }})</span>
        @endif
    </div>

    @if($posts->count())
    <div class="space-y-4">
        @foreach($posts as $post)
        @include('components.post-card', ['post' => $post, 'variant' => 'list'])
        @endforeach
    </div>
    {{ $posts->appends(['q' => $query])->links() }}
    @else
    <div class="text-center py-20">
        <svg class="w-16 h-16 text-gray-200 dark:text-gray-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <p class="text-gray-500 text-lg">{{ $locale === 'dv' ? '"' . $query . '" ހޯދިޔަ ނަތީޖާ ލިބޭ ގޮތެއް ނެތް' : 'No results found for "' . $query . '"' }}</p>
        <p class="text-gray-400 text-sm mt-2">{{ $locale === 'dv' ? 'ތަފާތު ބަހެއް ލިޔެ ބަލާ' : 'Try different keywords' }}</p>
    </div>
    @endif
    @endif
</div>
@endsection
