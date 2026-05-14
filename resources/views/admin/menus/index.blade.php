@extends('layouts.admin')
@section('title', 'Menus')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Menus</h1>
    <a href="{{ route('admin.menus.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">+ New Menu</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($menus as $menu)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $menu->name }}</h3>
            <div class="flex gap-2">
                <a href="{{ route('admin.menus.edit', $menu) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                </form>
            </div>
        </div>
        <p class="text-xs text-gray-500 font-mono">Location: {{ $menu->location }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $menu->items_count ?? 0 }} items</p>
    </div>
    @empty
    <div class="col-span-2 py-12 text-center text-gray-400">No menus yet.</div>
    @endforelse
</div>
@endsection
