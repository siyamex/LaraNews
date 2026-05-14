@extends('install.layout')

@php
$steps = ['Requirements', 'Database', 'Site Settings', 'Admin Account'];
$currentStep = 3;
@endphp

@section('card')
<div class="p-8">
    <h2 class="text-lg font-black text-gray-900 mb-1">Site Settings</h2>
    <p class="text-sm text-gray-500 mb-6">Configure your site name, URL, and locale.</p>

    @if($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        {{ $errors->first() }}
    </div>
    @endif

    <form action="{{ route('install.site.setup') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
            <input type="text" name="app_name" value="{{ old('app_name', 'LaraNews') }}"
                   class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Site URL</label>
            <input type="url" name="app_url" value="{{ old('app_url', request()->root()) }}"
                   class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500"
                   required placeholder="https://example.com">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Default Language</label>
            <select name="app_locale"
                    class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500">
                <option value="dv" {{ old('app_locale') === 'dv' ? 'selected' : '' }}>ދިވެހި (Dhivehi)</option>
                <option value="en" {{ old('app_locale') === 'en' ? 'selected' : '' }}>English</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Environment</label>
            <select name="app_env"
                    class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500">
                <option value="production" selected>Production</option>
                <option value="local">Local (Development)</option>
            </select>
        </div>

        <div class="pt-2 flex items-center justify-between">
            <a href="{{ route('install.database') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
            <button type="submit"
                    class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-colors">
                Save & Continue →
            </button>
        </div>
    </form>
</div>
@endsection
