@extends('layouts.app')

@section('content')
@php $isRtl = $locale === 'dv'; @endphp

<div class="container mx-auto px-4 py-8 max-w-7xl">

    {{-- Category Header --}}
    <div class="relative rounded-3xl overflow-hidden mb-8 p-8 {{ $category->color ? '' : 'bg-gradient-to-r from-red-600 to-red-800' }}"
         style="{{ $category->color ? 'background: linear-gradient(135deg, ' . $category->color . '22, ' . $category->color . '44)' : '' }}">
        <div class="flex items-center gap-4">
            @if($category->icon)
            <span class="text-5xl">{{ $category->icon }}</span>
            @endif
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white">{{ $category->getName($locale) }}</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $posts->total() }} {{ $locale === 'dv' ? 'ހަބަރު' : 'stories' }}</p>
            </div>
        </div>

        {{-- Subcategories --}}
        @if($category->children->count())
        <div class="flex flex-wrap gap-2 mt-5">
            @foreach($category->children as $child)
            <a href="{{ route('category.show', ['locale' => $locale, 'slug' => $child->getSlugForLocale($locale)]) }}"
               class="px-3 py-1.5 bg-white/80 dark:bg-gray-800/80 text-sm font-medium rounded-full hover:bg-white transition-colors">
                {{ $child->getName($locale) }}
            </a>
            @endforeach
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            @forelse($posts as $post)
            @include('components.post-card', ['post' => $post, 'variant' => 'list'])
            @empty
            <p class="text-center text-gray-400 py-12">{{ $locale === 'dv' ? 'ހަބަރެއް ނެތް' : 'No stories in this category yet.' }}</p>
            @endforelse

            {{ $posts->links() }}
        </div>

        <aside class="space-y-6">
            @include('components.ad-zone', ['placement' => 'sidebar'])
        </aside>
    </div>
</div>
@endsection
