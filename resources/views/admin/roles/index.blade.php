@extends('layouts.admin')
@section('title', 'Roles & Permissions')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Roles & Permissions</h1>
    <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">+ New Role</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">Role</th>
                <th class="px-4 py-3 text-start">Permissions</th>
                <th class="px-4 py-3 text-center">Users</th>
                <th class="px-4 py-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($roles as $role)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $role->name }}</td>
                <td class="px-4 py-3">
                    <div class="flex flex-wrap gap-1">
                        @foreach($role->permissions->take(5) as $perm)
                            <span class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded text-xs">{{ $perm->name }}</span>
                        @endforeach
                        @if($role->permissions->count() > 5)
                            <span class="px-1.5 py-0.5 text-gray-500 text-xs">+{{ $role->permissions->count() - 5 }} more</span>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $role->users_count ?? 0 }}</td>
                <td class="px-4 py-3 text-end">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-12 text-center text-gray-400">No roles found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
