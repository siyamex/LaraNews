@extends('layouts.admin')
@section('title', 'Activity Logs')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Activity Logs</h1>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">User</th>
                <th class="px-4 py-3 text-start">Event</th>
                <th class="px-4 py-3 text-start">Subject</th>
                <th class="px-4 py-3 text-start">IP</th>
                <th class="px-4 py-3 text-center">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-900 dark:text-white text-xs">{{ $log->causer?->name ?? 'System' }}</p>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        {{ match($log->event) { 'created'=>'bg-green-100 text-green-800', 'updated'=>'bg-blue-100 text-blue-800', 'deleted'=>'bg-red-100 text-red-800', default=>'bg-gray-100 text-gray-800' } }}">
                        {{ $log->event }}
                    </span>
                </td>
                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">
                    {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                </td>
                <td class="px-4 py-3 text-xs text-gray-500 font-mono">{{ $log->properties['ip'] ?? '-' }}</td>
                <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No activity logs found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $logs->links() }}</div>
</div>
@endsection
