@extends('layouts.front', ['locale' => $locale])
@section('title', __('Privacy Settings') . ' — ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-10">
<div class="container mx-auto px-4 max-w-2xl">

    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Privacy & Data Settings</h1>
    <p class="text-gray-500 dark:text-gray-400 mb-8">Manage how your data is stored and used.</p>

    {{-- Cookie Preferences --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6"
         x-data="{
             analytics:   {{ session('cookie_consent.analytics', true) ? 'true' : 'false' }},
             marketing:   {{ session('cookie_consent.marketing', false) ? 'true' : 'false' }},
             preferences: {{ session('cookie_consent.preferences', true) ? 'true' : 'false' }},
             saved: false,
             async save() {
                 await fetch('{{ route('privacy.consent', ['locale' => $locale]) }}', {
                     method: 'POST',
                     headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                     body: JSON.stringify({ analytics: this.analytics, marketing: this.marketing, preferences: this.preferences })
                 });
                 this.saved = true;
                 setTimeout(() => this.saved = false, 3000);
             }
         }">
        <h2 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Cookie Preferences
        </h2>
        <div class="space-y-4">
            @foreach([
                ['key' => 'analytics',   'label' => 'Analytics Cookies',    'desc' => 'Help us understand how visitors use the site (Google Analytics, Plausible, etc.)'],
                ['key' => 'marketing',   'label' => 'Marketing Cookies',    'desc' => 'Used to show you relevant advertisements and track ad performance.'],
                ['key' => 'preferences','label' => 'Preference Cookies',   'desc' => 'Remember your settings like language, theme, and font size.'],
            ] as $cookie)
            <label class="flex items-start gap-4 cursor-pointer group">
                <button type="button"
                        @click="{{ $cookie['key'] }} = !{{ $cookie['key'] }}"
                        :class="{{ $cookie['key'] }} ? 'bg-red-600' : 'bg-gray-200 dark:bg-gray-700'"
                        class="relative shrink-0 w-11 h-6 rounded-full transition-colors mt-0.5">
                    <span :class="{{ $cookie['key'] }} ? 'translate-x-5' : 'translate-x-1'"
                          class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"></span>
                </button>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $cookie['label'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $cookie['desc'] }}</p>
                </div>
            </label>
            @endforeach
        </div>
        <button @click="save()" class="mt-5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
            Save Preferences
        </button>
        <span x-show="saved" x-transition class="ms-3 text-sm text-green-600 font-medium">Saved!</span>
    </div>

    {{-- Data Export --}}
    @auth
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
        <h2 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export My Data
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Download a JSON file with all your account data — bookmarks, comments, and reading history.</p>
        <a href="{{ route('privacy.export', ['locale' => $locale]) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download My Data
        </a>
    </div>

    {{-- Account Deletion --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-red-200 dark:border-red-900/50 p-6"
         x-data="{ open: false }">
        <h2 class="font-semibold text-red-600 dark:text-red-400 mb-2 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete My Account
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">This action is irreversible. All your personal data will be anonymized.</p>
        <button @click="open=true" class="px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
            Request Account Deletion
        </button>

        <div x-show="open" x-transition class="mt-5 border-t border-gray-100 dark:border-gray-700 pt-5">
            <form method="POST" action="{{ route('privacy.delete', ['locale' => $locale]) }}">
                @csrf
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">Type <strong>DELETE MY ACCOUNT</strong> and enter your password to confirm.</p>
                <input type="text" name="confirm_phrase" placeholder="DELETE MY ACCOUNT"
                       class="block w-full mb-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                <input type="password" name="password" placeholder="Your password"
                       class="block w-full mb-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                @error('password') <p class="text-xs text-red-600 mb-3">{{ $message }}</p> @enderror
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                    Permanently Delete Account
                </button>
                <button type="button" @click="open=false" class="ms-3 px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-800">
                    Cancel
                </button>
            </form>
        </div>
    </div>
    @endauth

</div>
</div>
@endsection
