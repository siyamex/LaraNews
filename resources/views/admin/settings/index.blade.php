@extends('layouts.admin')
@section('title', 'Settings')
@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-700 dark:text-gray-300">Settings</span>
@endsection

@section('content')
<h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Settings</h1>

@foreach(['general','seo','social','widgets','mail'] as $group)
@php $groupSettings = $settings->filter(fn($s) => $s->group === $group); @endphp
@if($groupSettings->count())
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white capitalize">{{ $group }} Settings</h2>
    </div>
    <form action="{{ route('admin.settings.update', $group) }}" method="POST" class="p-6 space-y-4">
        @csrf @method('PUT')
        @foreach($groupSettings as $key => $setting)
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ ucwords(str_replace(['_','-'], ' ', $setting->key)) }}
            </label>
            @if($setting->type === 'boolean')
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="{{ $setting->key }}" value="true"
                           {{ $setting->value === 'true' ? 'checked' : '' }}
                           class="rounded border-gray-300 text-red-600">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Enabled</span>
                </label>
            @elseif($setting->type === 'text' || strlen($setting->value ?? '') > 80)
                <textarea name="{{ $setting->key }}" rows="3"
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm text-sm">{{ $setting->value }}</textarea>
            @else
                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm text-sm">
            @endif
        </div>
        @endforeach
        <div>
            <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                Save {{ ucfirst($group) }} Settings
            </button>
        </div>
    </form>
</div>
@endif
@endforeach
@endsection
