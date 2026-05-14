@extends('layouts.admin')
@section('title', 'Newsletter Subscribers')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Newsletter Subscribers</h1>
    <div class="flex gap-3">
        <a href="{{ route('admin.newsletter.export') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">Export CSV</a>
        <a href="{{ route('admin.newsletter.campaigns.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg">Campaigns</a>
    </div>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">Email</th>
                <th class="px-4 py-3 text-start">Name</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-center">Locale</th>
                <th class="px-4 py-3 text-center">Subscribed</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($subscribers as $sub)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $sub->email }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $sub->name }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $sub->status === 'subscribed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($sub->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center text-xs text-gray-500 uppercase">{{ $sub->locale }}</td>
                <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $sub->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No subscribers yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $subscribers->links() }}</div>
</div>
@endsection
