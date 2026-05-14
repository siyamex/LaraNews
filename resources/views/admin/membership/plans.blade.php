@extends('layouts.admin')
@section('title', 'Membership Plans')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Membership Plans</h1>
    <a href="{{ route('admin.membership-plans.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">+ New Plan</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($plans as $plan)
    @php $planName = is_array($plan->name) ? ($plan->name['en'] ?? '') : $plan->name; @endphp
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-2 {{ $plan->is_featured ? 'border-red-500' : 'border-transparent' }}">
        @if($plan->is_featured)
        <span class="inline-block px-2 py-0.5 bg-red-100 text-red-800 text-xs font-medium rounded mb-2">Most Popular</span>
        @endif
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $planName }}</h3>
        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $plan->currency }} {{ number_format($plan->price, 2) }}
            <span class="text-sm font-normal text-gray-500">/ {{ $plan->interval }}</span>
        </p>
        <div class="mt-4 flex gap-2">
            <a href="{{ route('admin.membership-plans.edit', $plan) }}" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-lg">Edit</a>
            <form action="{{ route('admin.membership-plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg">Delete</button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-12 text-center text-gray-400">No membership plans yet.</div>
    @endforelse
</div>
<div class="mt-8">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Subscriptions</h2>
    <a href="{{ route('admin.subscriptions.index') }}" class="text-sm text-red-600 hover:text-red-700">View all subscriptions →</a>
</div>
@endsection
