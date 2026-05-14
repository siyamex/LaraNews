@extends('layouts.admin')
@section('title', 'Edit Tag')
@section('content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Tag</h1>
    <form action="{{ route('admin.tags.update', $tag) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        @php $dvTrans = $tag->translations->where('locale','dv')->first(); $enTrans = $tag->translations->where('locale','en')->first(); @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">English Name</label>
                <input type="text" name="translations[en][name]" value="{{ old('translations.en.name', $enTrans?->name) }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dhivehi Name</label>
                <input type="text" name="translations[dv][name]" value="{{ old('translations.dv.name', $dvTrans?->name) }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" dir="rtl">
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Update Tag</button>
            <a href="{{ route('admin.tags.index') }}" class="px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
