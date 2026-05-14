@extends('install.layout')

@php
$steps = ['Requirements', 'Database', 'Site Settings', 'Admin Account'];
$currentStep = 1;
@endphp

@section('card')
<div class="p-8">
    <h2 class="text-lg font-black text-gray-900 mb-1">Server Requirements</h2>
    <p class="text-sm text-gray-500 mb-6">Checking if your server meets all requirements.</p>

    <div class="space-y-2">
        @foreach($checks as $check)
        <div class="flex items-center justify-between py-3 px-4 rounded-xl
                    {{ $check['pass'] ? 'bg-green-50' : 'bg-red-50' }}">
            <div class="flex items-center gap-3">
                @if($check['pass'])
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                @else
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                @endif
                <span class="text-sm font-medium {{ $check['pass'] ? 'text-green-800' : 'text-red-800' }}">
                    {{ $check['label'] }}
                </span>
            </div>
            <span class="text-xs font-mono {{ $check['pass'] ? 'text-green-600' : 'text-red-600' }}">
                {{ $check['current'] }}
            </span>
        </div>
        @endforeach
    </div>

    <div class="mt-6 flex items-center justify-between">
        <a href="{{ route('install.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
        @if($allPassed)
        <a href="{{ route('install.database') }}"
           class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-colors">
            Continue →
        </a>
        @else
        <button disabled class="px-6 py-2.5 bg-gray-300 text-gray-500 text-sm font-bold rounded-xl cursor-not-allowed">
            Fix issues to continue
        </button>
        @endif
    </div>
</div>
@endsection
