@extends('layouts.admin')
@section('title', 'Coupons')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Coupons</h1>
    <a href="{{ route('admin.coupons.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">+ New Coupon</a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-start">Code</th>
                <th class="px-4 py-3 text-start">Discount</th>
                <th class="px-4 py-3 text-center">Uses</th>
                <th class="px-4 py-3 text-center">Expires</th>
                <th class="px-4 py-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($coupons as $coupon)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-4 py-3 font-mono font-semibold text-gray-900 dark:text-white">{{ $coupon->code }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                    {{ $coupon->type === 'percent' ? $coupon->value.'%' : number_format($coupon->value, 2) }}
                    {{ $coupon->type === 'fixed' ? $coupon->currency : '' }}
                </td>
                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">
                    {{ $coupon->uses_count }}/{{ $coupon->max_uses ?? '∞' }}
                </td>
                <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $coupon->expires_at?->format('M d, Y') ?? 'Never' }}</td>
                <td class="px-4 py-3 text-end">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-blue-600 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-xs font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No coupons yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $coupons->links() }}</div>
</div>
@endsection
