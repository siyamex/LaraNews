@extends('layouts.admin')
@section('title', 'New Role')
@section('content')
<div class="max-w-xl">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">New Role</h1>
    <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permissions</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($permissions as $permission)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                               class="rounded border-gray-300 text-red-600">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Save Role</button>
            <a href="{{ route('admin.roles.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
