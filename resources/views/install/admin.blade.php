@extends('install.layout')

@php
$steps = ['Requirements', 'Database', 'Site Settings', 'Admin Account'];
$currentStep = 4;
@endphp

@section('card')
<div class="p-8">
    <h2 class="text-lg font-black text-gray-900 mb-1">Admin Account</h2>
    <p class="text-sm text-gray-500 mb-6">Create the super admin account. You'll use this to log in.</p>

    @if($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <form action="{{ route('install.run') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500"
                   required placeholder="Admin User">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500"
                   required placeholder="admin@example.com">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password"
                   class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500"
                   required minlength="8">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500"
                   required minlength="8">
        </div>

        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-700">
            This will run database migrations, create your admin account, and finalize the installation. This action cannot be undone.
        </div>

        <div class="pt-2 flex items-center justify-between">
            <a href="{{ route('install.site') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
            <button type="submit"
                    class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-colors">
                Install LaraNews →
            </button>
        </div>
    </form>
</div>
@endsection
