@extends('layouts.admin')
@section('title', 'RSS Sources')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">RSS Import Sources</h1>
    <a href="{{ route('admin.rss-sources.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">+ New Source</a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">Name</th>
                <th class="px-4 py-3 text-start">URL</th>
                <th class="px-4 py-3 text-center">Category</th>
                <th class="px-4 py-3 text-center">Auto Import</th>
                <th class="px-4 py-3 text-center">Last Import</th>
                <th class="px-4 py-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($sources as $source)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $source->name }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs truncate max-w-xs">{{ $source->url }}</td>
                <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $source->category?->translations->where('locale','en')->first()?->name }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $source->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ $source->is_active ? 'Yes' : 'No' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $source->last_imported_at?->diffForHumans() ?? 'Never' }}</td>
                <td class="px-4 py-3 text-end">
                    <div class="flex items-center justify-end gap-2">
                        <form action="{{ route('admin.rss-sources.import', $source) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 text-xs font-medium">Import Now</button>
                        </form>
                        <a href="{{ route('admin.rss-sources.edit', $source) }}" class="text-blue-600 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.rss-sources.destroy', $source) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-xs font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">No RSS sources yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $sources->links() }}</div>
</div>
@endsection
