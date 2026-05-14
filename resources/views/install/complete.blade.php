@extends('install.layout')

@section('card')
<div class="p-8 text-center">
    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
    </div>

    <h2 class="text-xl font-black text-gray-900 mb-2">Installation Complete!</h2>
    <p class="text-gray-500 text-sm mb-8 max-w-sm mx-auto">
        LaraNews has been successfully installed. You can now log in to the admin panel.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
        <a href="{{ url('/') }}"
           class="flex items-center justify-center gap-2 px-5 py-3 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            View Site
        </a>
        <a href="{{ url('/admin') }}"
           class="flex items-center justify-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors text-sm font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Go to Admin Panel
        </a>
    </div>

    <div class="bg-gray-50 rounded-xl p-4 text-left text-xs text-gray-500 space-y-1">
        <p class="font-semibold text-gray-700 mb-2">Post-installation checklist</p>
        <div class="space-y-1">
            <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="rounded"> Configure email settings (SMTP) in Admin → Settings</label>
            <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="rounded"> Set up categories and tags</label>
            <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="rounded"> Configure the RSS importer</label>
            <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="rounded"> Upload your site logo and favicon</label>
            <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="rounded"> Set up a cron job: <code class="bg-gray-200 px-1 rounded">* * * * * php /path/to/artisan schedule:run</code></label>
        </div>
    </div>
</div>
@endsection
