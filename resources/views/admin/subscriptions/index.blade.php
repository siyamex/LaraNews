@extends('layouts.admin')
@section('title', 'Subscriptions')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Subscriptions</h1>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">User</th>
                <th class="px-4 py-3 text-start">Plan</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-center">Amount</th>
                <th class="px-4 py-3 text-center">Expires</th>
                <th class="px-4 py-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($subscriptions as $sub)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-900 dark:text-white text-xs">{{ $sub->user?->name }}</p>
                    <p class="text-xs text-gray-500">{{ $sub->user?->email }}</p>
                </td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs">{{ $sub->plan?->slug }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $sub->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($sub->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center text-xs">{{ $sub->plan?->currency }} {{ number_format($sub->plan?->price ?? 0, 2) }}</td>
                <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $sub->ends_at?->format('M d, Y') ?? '∞' }}</td>
                <td class="px-4 py-3 text-end">
                    <form action="{{ route('admin.subscriptions.destroy', $sub) }}" method="POST" onsubmit="return confirm('Cancel subscription?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Cancel</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">No subscriptions yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $subscriptions->links() }}</div>
</div>
@endsection
