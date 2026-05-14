@extends('layouts.admin')
@section('title', 'Subscription Details')
@section('content')
<div class="max-w-xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Subscription #{{ $subscription->id }}</h1>
        <a href="{{ route('admin.subscriptions.index') }}" class="text-sm text-red-600 hover:text-red-700">← Back</a>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div><dt class="text-gray-500">User</dt><dd class="font-medium dark:text-white">{{ $subscription->user?->name }}</dd></div>
            <div><dt class="text-gray-500">Email</dt><dd class="font-medium dark:text-white">{{ $subscription->user?->email }}</dd></div>
            <div><dt class="text-gray-500">Plan</dt><dd class="font-medium dark:text-white">{{ $subscription->plan?->slug }}</dd></div>
            <div><dt class="text-gray-500">Status</dt><dd class="font-medium dark:text-white">{{ ucfirst($subscription->status) }}</dd></div>
            <div><dt class="text-gray-500">Amount</dt><dd class="font-medium dark:text-white">{{ $subscription->plan?->currency }} {{ number_format($subscription->plan?->price ?? 0, 2) }}</dd></div>
            <div><dt class="text-gray-500">Expires</dt><dd class="font-medium dark:text-white">{{ $subscription->ends_at?->format('M d, Y') ?? '∞' }}</dd></div>
            <div><dt class="text-gray-500">Started</dt><dd class="font-medium dark:text-white">{{ $subscription->created_at->format('M d, Y') }}</dd></div>
        </dl>
    </div>
</div>
@endsection
