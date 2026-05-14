@extends('layouts.app')

@section('title', __('Membership') . ' — ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">{{ __('Choose Your Plan') }}</h1>
        <p class="mt-4 text-xl text-gray-500">{{ __('Support quality journalism and get unlimited access') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-{{ count($plans) }} gap-8 max-w-5xl mx-auto">
        @foreach($plans as $plan)
        @php
            $planName = is_array($plan->name) ? ($plan->name[app()->getLocale()] ?? $plan->name['en'] ?? '') : $plan->name;
            $features = is_array($plan->features) ? $plan->features : json_decode($plan->features, true);
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border-2 {{ $plan->is_featured ? 'border-red-500 shadow-red-100 dark:shadow-none' : 'border-transparent' }} p-8 flex flex-col">
            @if($plan->is_featured)
            <div class="inline-block px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full mb-4 self-start">
                {{ __('Most Popular') }}
            </div>
            @endif

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $planName }}</h2>
            <div class="mt-4 mb-6">
                <span class="text-4xl font-bold text-gray-900 dark:text-white">{{ $plan->currency }} {{ number_format($plan->price, 0) }}</span>
                <span class="text-gray-500">/ {{ $plan->interval === 'monthly' ? __('month') : __('year') }}</span>
            </div>

            @if($features)
            <ul class="space-y-3 mb-8 flex-1">
                @foreach($features as $feature)
                <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>
            @endif

            @auth
            <form action="{{ route('membership.subscribe', ['locale' => app()->getLocale(), 'plan' => $plan]) }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full py-3 px-6 {{ $plan->is_featured ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white' }} font-semibold rounded-xl transition-colors">
                    {{ __('Subscribe') }}
                </button>
            </form>
            @else
            <a href="{{ route('login') }}"
               class="w-full py-3 px-6 text-center {{ $plan->is_featured ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-900' }} font-semibold rounded-xl transition-colors block">
                {{ __('Sign in to subscribe') }}
            </a>
            @endauth
        </div>
        @endforeach
    </div>

    <p class="text-center text-sm text-gray-500 mt-8">
        {{ __('All plans include a 7-day free trial. Cancel anytime.') }}
    </p>
</div>
@endsection
