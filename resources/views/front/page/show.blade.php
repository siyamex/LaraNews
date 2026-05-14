@extends('layouts.app')

@section('title', $translation?->meta_title ?? $translation?->title ?? config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-12 max-w-4xl">
    <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 md:p-12">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-8">
            {{ $translation?->title }}
        </h1>

        <div class="prose dark:prose-invert max-w-none {{ app()->getLocale() === 'dv' ? 'text-right font-thaana' : '' }}">
            {!! $translation?->content !!}
        </div>
    </article>
</div>
@endsection
