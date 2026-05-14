@extends('layouts.app')

@section('title', __('Subscription Active') . ' — ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-16 text-center">
    <div class="max-w-md mx-auto">
        <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Subscription Active!') }}</h1>
        <p class="text-gray-500 mb-8">
            {{ __('Welcome to premium membership. You now have unlimited access to all articles.') }}
        </p>

        <div class="flex gap-4 justify-center">
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}"
               class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl">
                {{ __('Read News') }}
            </a>
            <a href="{{ route('profile.show') }}"
               class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-50">
                {{ __('My Account') }}
            </a>
        </div>
    </div>
</div>
@endsection
