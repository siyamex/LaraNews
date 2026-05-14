@extends('layouts.admin')
@section('title', 'Font Manager')

@section('breadcrumb')
    <span class="text-gray-400">/</span>
    <span>Font Manager</span>
@endsection

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Font Manager</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload and manage custom web fonts.</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Font List --}}
    <div class="xl:col-span-2 space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 font-semibold text-gray-900 dark:text-white">
                Installed Fonts ({{ count($fonts) }})
            </div>
            @forelse($fonts as $font)
            <div class="flex items-center gap-4 px-5 py-4 border-b border-gray-50 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0">
                    <span class="text-xs font-bold text-gray-500 uppercase">{{ strtoupper(pathinfo($font['file'], PATHINFO_EXTENSION)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $font['name'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Weight: {{ $font['weight'] }} · Style: {{ ucfirst($font['style']) }} · Format: {{ strtoupper($font['format']) }}</p>
                    <p class="text-xs text-gray-400 font-mono truncate mt-0.5">{{ $font['file'] }}</p>
                </div>
                <form method="POST" action="{{ route('admin.fonts.destroy', $font['slug']) }}" class="shrink-0">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this font?')"
                            class="opacity-0 group-hover:opacity-100 transition-opacity text-red-500 hover:text-red-700 p-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
            @empty
            <div class="px-5 py-10 text-center text-gray-400 text-sm">
                No custom fonts uploaded yet. Use the form to upload your first font.
            </div>
            @endforelse
        </div>

        {{-- Active Font Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Active Font Assignment</h3>
            <form method="POST" action="{{ route('admin.fonts.set-active') }}" class="grid grid-cols-2 gap-4">
                @csrf
                @php
                    $headingFont = \App\Models\Setting::where('key', 'heading_font')->value('value');
                    $bodyFont    = \App\Models\Setting::where('key', 'body_font')->value('value');
                    $fontNames   = collect($fonts)->pluck('name')->unique()->values();
                @endphp
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Heading Font</label>
                    <select name="heading_font" class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2">
                        <option value="">System Default</option>
                        @foreach($fontNames as $name)
                        <option value="{{ $name }}" @selected($headingFont === $name)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Body Font</label>
                    <select name="body_font" class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2">
                        <option value="">System Default</option>
                        @foreach($fontNames as $name)
                        <option value="{{ $name }}" @selected($bodyFont === $name)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">Save Font Settings</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Upload Form --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Upload Font</h3>
        <form method="POST" action="{{ route('admin.fonts.upload') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Font File</label>
                <input type="file" name="font_file" accept=".ttf,.otf,.woff,.woff2"
                       class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                @error('font_file') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Supported: TTF, OTF, WOFF, WOFF2 (max 5MB)</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Font Family Name</label>
                <input type="text" name="font_name" placeholder="e.g. Dhivehi Sans"
                       class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2 focus:ring-2 focus:ring-red-500">
                @error('font_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Weight</label>
                    <select name="font_weight" class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2">
                        @foreach([100,200,300,400,500,600,700,800,900] as $w)
                        <option value="{{ $w }}" @selected($w === 400)>{{ $w }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Style</label>
                    <select name="font_style" class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm px-3 py-2">
                        <option value="normal">Normal</option>
                        <option value="italic">Italic</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Upload Font
            </button>
        </form>
    </div>

</div>
@endsection
