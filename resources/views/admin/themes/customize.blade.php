@extends('layouts.admin')
@section('title', 'Customize Theme')

@section('breadcrumb')
<span>›</span> <a href="{{ route('admin.themes.index') }}" class="hover:text-gray-700 dark:hover:text-gray-300">Themes</a>
<span>›</span> <span class="text-gray-700 dark:text-gray-300">{{ $current['name'] }}</span>
@endsection

@section('content')
<div class="space-y-6" x-data="{
    primary: '{{ $current['primary'] }}',
    primary_dark: '{{ $current['primary_dark'] }}',
    primary_hover: '{{ $current['primary_hover'] }}',
    primary_light: '{{ $current['primary_light'] }}',
    syncText(field, val) { this[field] = val; }
}">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">Customize: {{ $current['name'] }}</h1>
            <p class="text-sm text-gray-500 mt-1">Fine-tune the colour values for this preset.</p>
        </div>
        <a href="{{ route('admin.themes.index') }}"
           class="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Themes
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Colour editor --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-5">Colour Palette</h2>

            <form action="{{ route('admin.themes.settings', $theme) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')

                @foreach([
                    'primary'       => ['label' => 'Primary',        'desc' => 'Main accent — buttons, links, nav bar'],
                    'primary_dark'  => ['label' => 'Primary Dark',   'desc' => 'Hover / darker shade'],
                    'primary_hover' => ['label' => 'Primary Deeper', 'desc' => 'Active / pressed state'],
                    'primary_light' => ['label' => 'Primary Light',  'desc' => 'Background tints, badge fills'],
                ] as $field => $meta)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-0.5">{{ $meta['label'] }}</label>
                    <p class="text-xs text-gray-400 mb-2">{{ $meta['desc'] }}</p>
                    <div class="flex items-center gap-3">
                        <input type="color"
                               name="{{ $field }}"
                               x-model="{{ $field }}"
                               value="{{ $current[$field] }}"
                               class="w-11 h-11 rounded-lg cursor-pointer border border-gray-300 dark:border-gray-600 p-0.5 bg-white dark:bg-gray-700">
                        <input type="text"
                               :value="{{ $field }}"
                               @input="syncText('{{ $field }}', $event.target.value)"
                               class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm text-sm font-mono"
                               placeholder="#000000"
                               pattern="^#[0-9A-Fa-f]{6}$">
                    </div>
                </div>
                @endforeach

                <div class="pt-2 flex gap-3">
                    <button type="submit"
                            class="px-6 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-lg hover:opacity-90 transition-opacity">
                        Save Customizations
                    </button>
                    <a href="{{ route('admin.themes.index') }}"
                       class="px-6 py-2.5 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        {{-- Live preview panel --}}
        <div class="space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5">
                <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">Live Preview</h2>

                {{-- Mock nav bar --}}
                <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="h-10 flex items-center px-3 gap-2 text-white text-xs font-medium"
                         :style="'background:' + primary">
                        <span class="w-4 h-4 bg-white/30 rounded block"></span>
                        <span class="flex-1 font-bold tracking-wide opacity-90">NEWS</span>
                        <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs">Subscribe</span>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-900 space-y-2">
                        {{-- Mock card --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-100 dark:border-gray-700">
                            <span class="inline-block px-2 py-0.5 text-white text-xs rounded-full mb-2"
                                  :style="'background:' + primary">Category</span>
                            <div class="h-2.5 bg-gray-200 dark:bg-gray-600 rounded w-full mb-1.5"></div>
                            <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded w-3/4"></div>
                            <div class="mt-3 flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-600"></div>
                                <span class="text-xs font-medium" :style="'color:' + primary">Read more →</span>
                            </div>
                        </div>
                        {{-- Progress bar mock --}}
                        <div class="h-0.5 rounded-full w-3/4" :style="'background:' + primary"></div>
                    </div>
                </div>

                {{-- Swatch row --}}
                <div class="flex gap-2 mt-4">
                    <div class="flex-1 text-center">
                        <div class="w-full h-8 rounded-lg mb-1" :style="'background:' + primary"></div>
                        <span class="text-xs text-gray-400">Primary</span>
                    </div>
                    <div class="flex-1 text-center">
                        <div class="w-full h-8 rounded-lg mb-1" :style="'background:' + primary_dark"></div>
                        <span class="text-xs text-gray-400">Dark</span>
                    </div>
                    <div class="flex-1 text-center">
                        <div class="w-full h-8 rounded-lg border border-gray-200 dark:border-gray-600 mb-1" :style="'background:' + primary_light"></div>
                        <span class="text-xs text-gray-400">Light</span>
                    </div>
                </div>
            </div>

            {{-- Reset to preset defaults --}}
            <form action="{{ route('admin.themes.settings', $theme) }}" method="POST">
                @csrf @method('PUT')
                @foreach(['primary','primary_dark','primary_hover','primary_light'] as $f)
                <input type="hidden" name="{{ $f }}" value="{{ $presets[$theme][$f] }}">
                @endforeach
                <button type="submit"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Reset to Preset Defaults
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
