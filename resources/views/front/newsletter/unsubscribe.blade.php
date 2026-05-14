@extends('layouts.app')
@section('title', __('Unsubscribed'))
@section('content')
<div class="container mx-auto px-4 py-16 text-center max-w-lg">
    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
        </svg>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Unsubscribed') }}</h1>
    <p class="text-gray-500 mb-8">{{ __('You have been unsubscribed from our newsletter. We are sorry to see you go.') }}</p>
    <a href="{{ route('home', ['locale' => app()->getLocale()]) }}"
       class="px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl">
        {{ __('Go to Homepage') }}
    </a>
</div>
@endsection
