@php
    $locale = app()->getLocale();
    $categories = \App\Models\Category::active()->with('translations')->orderBy('order')->take(12)->get();
@endphp

<div x-data="{ open: false }"
     @toggle-mobile-menu.window="open = !open"
     @keydown.escape.window="open = false">

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden backdrop-blur-sm"></div>

    {{-- Drawer --}}
    <div x-show="open"
         x-transition:enter="transition-transform duration-300 ease-out"
         x-transition:enter-start="{{ $locale === 'dv' ? 'translate-x-full' : '-translate-x-full' }}"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition-transform duration-250 ease-in"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="{{ $locale === 'dv' ? 'translate-x-full' : '-translate-x-full' }}"
         class="fixed inset-y-0 {{ $locale === 'dv' ? 'end-0' : 'start-0' }} w-[280px] bg-white dark:bg-gray-900 z-50 flex flex-col shadow-2xl lg:hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">
            <a href="{{ route('home', ['locale' => $locale]) }}" @click="open=false">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-8 w-auto"
                     onerror="this.onerror=null;this.style.display='none'">
                <span class="font-bold text-gray-900 dark:text-white text-base">{{ config('app.name') }}</span>
            </a>
            <button @click="open = false"
                    class="p-2 rounded-xl text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Nav Content --}}
        <div class="flex-1 overflow-y-auto py-4">

            {{-- Main Links --}}
            <div class="px-3 mb-4">
                <a href="{{ route('home', ['locale' => $locale]) }}" @click="open=false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ $locale === 'dv' ? 'ހޯމް' : 'Home' }}
                </a>
                <a href="{{ route('news.index', ['locale' => $locale]) }}" @click="open=false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('news.index') ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    {{ $locale === 'dv' ? 'ހަބަރު' : 'All News' }}
                </a>
            </div>

            {{-- Categories --}}
            <div class="px-3">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    {{ $locale === 'dv' ? 'ކެޓަގަރީ' : 'Categories' }}
                </p>
                <div class="space-y-0.5">
                    @foreach($categories as $cat)
                    <a href="{{ route('category.show', ['locale' => $locale, 'slug' => $cat->getSlugForLocale($locale)]) }}"
                       @click="open=false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                        @if($cat->icon)
                        <span class="text-base leading-none shrink-0">{{ $cat->icon }}</span>
                        @else
                        <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $cat->color ?? '#DC2626' }}"></span>
                        @endif
                        {{ $cat->getName($locale) }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="p-4 border-t border-gray-100 dark:border-gray-800 space-y-2">
            @auth
            <a href="{{ route('admin.dashboard') }}" @click="open=false"
               class="flex items-center justify-center gap-2 w-full py-2.5 bg-gray-900 dark:bg-gray-700 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 dark:hover:bg-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Admin Panel
            </a>
            @else
            <a href="{{ route('login') }}" @click="open=false"
               class="flex items-center justify-center gap-2 w-full py-2.5 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                Sign In
            </a>
            @endauth
            @if(!auth()->user()?->isSubscribed())
            <a href="{{ route('membership.plans', ['locale' => $locale]) }}" @click="open=false"
               class="flex items-center justify-center gap-2 w-full py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ $locale === 'dv' ? 'ޕްރިމިއަމް ލިބިދޭ' : 'Go Premium' }}
            </a>
            @endif
        </div>
    </div>
</div>
