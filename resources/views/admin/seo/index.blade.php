@extends('layouts.admin')
@section('title', 'SEO')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">SEO & Sitemaps</h1>
    <button onclick="generateSitemaps()" id="sitemap-btn"
            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Regenerate Sitemaps
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">SEO Settings</h2>
        <form action="{{ route('admin.settings.update', 'seo') }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            @foreach($settings as $key => $setting)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ ucwords(str_replace(['_','-'], ' ', $setting->key)) }}
                </label>
                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm text-sm">
            </div>
            @endforeach
            <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                Save SEO Settings
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sitemap Files</h2>
        <ul class="space-y-3">
            @foreach(['/sitemap.xml' => 'Main Sitemap', '/sitemap-news.xml' => 'Google News Sitemap', '/feed/en' => 'RSS Feed (EN)', '/feed/dv' => 'RSS Feed (DV)'] as $url => $name)
            <li class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $name }}</span>
                <a href="{{ $url }}" target="_blank" class="text-xs text-red-600 hover:text-red-700 font-medium">View →</a>
            </li>
            @endforeach
        </ul>
        <div id="sitemap-result" class="mt-4 hidden"></div>
    </div>
</div>

@push('scripts')
<script>
function generateSitemaps() {
    const btn = document.getElementById('sitemap-btn');
    btn.disabled = true;
    btn.textContent = 'Generating...';
    fetch('{{ route("admin.seo.generate-sitemaps") }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content}
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.textContent = 'Regenerate Sitemaps';
        const el = document.getElementById('sitemap-result');
        el.className = 'mt-4 p-3 bg-green-50 text-green-700 rounded-lg text-sm';
        el.textContent = data.message;
    })
    .catch(() => { btn.disabled = false; btn.textContent = 'Regenerate Sitemaps'; });
}
</script>
@endpush
@endsection
