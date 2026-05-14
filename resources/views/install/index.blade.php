@extends('install.layout')

@php
$steps = ['Requirements', 'Database', 'Site Settings', 'Admin Account'];
$currentStep = 1;
@endphp

@section('card')
<div class="p-8 text-center">
    <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
        </svg>
    </div>

    <h2 class="text-xl font-black text-gray-900 mb-2">Welcome to LaraNews</h2>
    <p class="text-gray-500 text-sm mb-8 max-w-sm mx-auto">
        This wizard will guide you through setting up your multilingual Dhivehi/English news platform.
        The process takes about 2 minutes.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left mb-8">
        @foreach([
            ['icon' => '🔧', 'text' => 'Check server requirements'],
            ['icon' => '🗄️', 'text' => 'Configure database connection'],
            ['icon' => '⚙️', 'text' => 'Set site name and URL'],
            ['icon' => '👤', 'text' => 'Create admin account'],
        ] as $step)
        <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3">
            <span class="text-xl">{{ $step['icon'] }}</span>
            <span class="text-sm text-gray-700">{{ $step['text'] }}</span>
        </div>
        @endforeach
    </div>

    <a href="{{ route('install.requirements') }}"
       class="inline-flex items-center gap-2 px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors">
        Get Started
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
    </a>
</div>
@endsection
