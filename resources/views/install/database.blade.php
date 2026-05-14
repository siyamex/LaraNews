@extends('install.layout')

@php
$steps = ['Requirements', 'Database', 'Site Settings', 'Admin Account'];
$currentStep = 2;
@endphp

@section('card')
<div class="p-8" x-data="{ testing: false, testResult: null }">
    <h2 class="text-lg font-black text-gray-900 mb-1">Database Connection</h2>
    <p class="text-sm text-gray-500 mb-6">Enter your MySQL database credentials.</p>

    @if($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        {{ $errors->first() }}
    </div>
    @endif

    <form action="{{ route('install.database.setup') }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-3 gap-3">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                <input type="text" name="db_host" value="{{ old('db_host', '127.0.0.1') }}"
                       class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500"
                       required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                <input type="number" name="db_port" value="{{ old('db_port', '3306') }}"
                       class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500"
                       required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
            <input type="text" name="db_name" value="{{ old('db_name', 'laranews') }}"
                   class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" name="db_username" value="{{ old('db_username', 'root') }}"
                   class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="db_password"
                   class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        {{-- Test connection --}}
        <div class="flex items-center gap-3">
            <button type="button"
                    @click="
                        testing = true;
                        testResult = null;
                        const data = new FormData(document.querySelector('form'));
                        fetch('{{ route('install.database.test') }}', {
                            method: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'},
                            body: data
                        })
                        .then(r => r.json())
                        .then(r => { testing = false; testResult = r; })
                        .catch(() => { testing = false; testResult = {success: false, message: 'Network error'}; });
                    "
                    class="px-4 py-2 border border-gray-300 text-gray-600 text-sm rounded-xl hover:bg-gray-50 transition-colors">
                <span x-show="!testing">Test Connection</span>
                <span x-show="testing" class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Testing...
                </span>
            </button>
            <span x-show="testResult?.success" class="text-sm text-green-600 font-medium">✓ Connected</span>
            <span x-show="testResult && !testResult.success" class="text-sm text-red-600" x-text="testResult?.message"></span>
        </div>

        <div class="pt-2 flex items-center justify-between">
            <a href="{{ route('install.requirements') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
            <button type="submit"
                    class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-colors">
                Save & Continue →
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
@endpush
@endsection
