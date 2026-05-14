@extends('layouts.app')
@section('title', __('Confirm Subscription'))
@section('content')
<div class="container mx-auto px-4 py-16 text-center max-w-lg">
    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
        </svg>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Subscription Confirmed!') }}</h1>
    <p class="text-gray-500 mb-8">{{ __('Thank you for subscribing to our newsletter. You will receive the latest news in your inbox.') }}</p>
    <a href="{{ route('home', ['locale' => app()->getLocale()]) }}"
       class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl">
        {{ __('Read Latest News') }}
    </a>
</div>
@endsection
