@extends('layouts.admin')
@section('title', 'Campaigns')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Email Campaigns</h1>
    <a href="{{ route('admin.newsletter.campaigns.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">+ New Campaign</a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">Subject</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-center">Recipients</th>
                <th class="px-4 py-3 text-center">Opens</th>
                <th class="px-4 py-3 text-center">Sent</th>
                <th class="px-4 py-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($campaigns as $campaign)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $campaign->subject }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ match($campaign->status) { 'sent'=>'bg-green-100 text-green-800', 'sending'=>'bg-blue-100 text-blue-800', 'scheduled'=>'bg-yellow-100 text-yellow-800', default=>'bg-gray-100 text-gray-600' } }}">
                        {{ ucfirst($campaign->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">{{ number_format($campaign->recipients_count) }}</td>
                <td class="px-4 py-3 text-center">{{ number_format($campaign->opens_count) }}</td>
                <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $campaign->sent_at?->format('M d, Y') ?? '-' }}</td>
                <td class="px-4 py-3 text-end">
                    <div class="flex items-center justify-end gap-2">
                        @if(in_array($campaign->status, ['draft', 'scheduled']))
                        <form action="{{ route('admin.newsletter.campaigns.send', $campaign) }}" method="POST" onsubmit="return confirm('Send campaign now?')">
                            @csrf
                            <button class="text-green-600 hover:text-green-800 text-xs font-medium">Send Now</button>
                        </form>
                        <a href="{{ route('admin.newsletter.campaigns.edit', $campaign) }}" class="text-blue-600 text-xs font-medium">Edit</a>
                        @endif
                        <form action="{{ route('admin.newsletter.campaigns.destroy', $campaign) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-xs font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">No campaigns yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $campaigns->links() }}</div>
</div>
@endsection
