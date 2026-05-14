@extends('layouts.admin')
@section('title', 'Edit Coupon')
@section('content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Coupon: {{ $coupon->code }}</h1>
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code</label>
                <input type="text" name="code" value="{{ old('code', $coupon->code) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm font-mono uppercase"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                    <select name="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                        <option value="percent" {{ $coupon->type === 'percent' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed" {{ $coupon->type === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    </select></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Value</label>
                    <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expires At</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm"></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Update Coupon</button>
            <a href="{{ route('admin.coupons.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
