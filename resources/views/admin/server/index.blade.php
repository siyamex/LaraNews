@extends('layouts.admin')

@section('title', 'Server Monitor')

@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span class="text-gray-700 dark:text-gray-300">Server Monitor</span>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Server Health & Cache</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitor system health and manage application cache.</p>
</div>

<livewire:admin.server-monitor />
@endsection
