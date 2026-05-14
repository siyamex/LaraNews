@extends('layouts.app')

@section('title', __('Checkout') . ' — ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-12 max-w-lg">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Complete Your Subscription') }}</h1>

    @php
        $planName = is_array($plan->name) ? ($plan->name[app()->getLocale()] ?? $plan->name['en'] ?? '') : $plan->name;
    @endphp

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 mb-6">
        <h2 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $planName }}</h2>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ $plan->currency }} {{ number_format($plan->price, 2) }}
            <span class="text-sm font-normal text-gray-500">/ {{ $plan->interval }}</span>
        </p>
    </div>

    <form action="{{ route('membership.subscribe', ['locale' => app()->getLocale(), 'plan' => $plan]) }}" method="POST"
          class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 space-y-4">
        @csrf
        <div id="stripe-card-element" class="p-3 border border-gray-300 dark:border-gray-600 rounded-lg min-h-10 bg-white"></div>
        <div id="card-errors" class="text-red-600 text-sm"></div>
        <button type="submit"
                class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors">
            {{ __('Subscribe Now') }}
        </button>
        <p class="text-xs text-gray-500 text-center">{{ __('Secure payment. Cancel anytime.') }}</p>
    </form>
</div>
@endsection
