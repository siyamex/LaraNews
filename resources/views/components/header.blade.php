@php
    $locale = app()->getLocale();
    $isRtl = $locale === 'dv';
    $categories = cache()->remember('menu_categories_'.$locale, 3600, fn() =>
        \App\Models\Category::active()->forMenu()->with(['translations','children.translations'])->get()
    );
@endphp

<header class="sticky top-0 z-40 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm border-b border-gray-100 dark:border-gray-800"
        x-data="{ megaMenu: null, scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 10">

    {{-- Top utility bar --}}
    <div class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/80">
        <div class="container mx-auto px-4 flex items-center justify-between h-8 text-xs text-gray-500 dark:text-gray-400">
            <time>{{ now()->format('l, d F Y') }}</time>
            <div class="flex items-center gap-3">
                {{-- Language Switcher --}}
                @php
                    $currentPath = request()->path();
                    $pathWithoutLocale = preg_replace('/^(en|dv)(\/|$)/', '', $currentPath);
                    $dvUrl = url('/dv/' . $pathWithoutLocale);
                    $enUrl = url('/en/' . $pathWithoutLocale);
                @endphp
                <div class="flex items-center gap-1.5">
                    <a href="{{ $dvUrl }}"
                       class="{{ $locale === 'dv' ? 'text-red-600 font-semibold' : 'hover:text-gray-700 dark:hover:text-gray-300' }} transition-colors">
                        ދިވެހި
                    </a>
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <a href="{{ $enUrl }}"
                       class="{{ $locale === 'en' ? 'text-red-600 font-semibold' : 'hover:text-gray-700 dark:hover:text-gray-300' }} transition-colors">
                        EN
                    </a>
                </div>
                {{-- Social --}}
                <div class="hidden sm:flex items-center gap-2">
                    @if($fb = \App\Models\Setting::get('facebook_url','','social'))
                    <a href="{{ $fb }}" target="_blank" rel="noopener" class="hover:text-blue-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif
                    @if($tw = \App\Models\Setting::get('twitter_url','','social'))
                    <a href="{{ $tw }}" target="_blank" rel="noopener" class="hover:text-gray-900 dark:hover:text-white transition-colors">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    @endif
                    @if($ig = \App\Models\Setting::get('instagram_url','','social'))
                    <a href="{{ $ig }}" target="_blank" rel="noopener" class="hover:text-pink-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Main Header --}}
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-3 h-16">

            {{-- Logo --}}
            <a href="{{ route('home', ['locale' => $locale]) }}" class="shrink-0 flex items-center gap-2">
                <div class="flex items-center justify-center w-9 h-9 bg-red-600 rounded-lg shrink-0">
                    <span class="text-white font-black text-sm leading-none">DN</span>
                </div>
                <span class="font-bold text-gray-900 dark:text-white text-lg hidden sm:block">{{ config('app.name') }}</span>
            </a>

            {{-- Desktop Search --}}
            <div class="flex-1 hidden lg:flex max-w-sm mx-auto">
                <livewire:front.search-bar />
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-1 ms-auto">

                {{-- Mobile Search --}}
                <button @click="$dispatch('search-open')"
                        class="lg:hidden p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                {{-- Dark mode --}}
                <button @click="$dispatch('toggle-dark')"
                        class="p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                {{-- Auth --}}
                @auth
                <div class="relative hidden sm:block" x-data="{ open: false }">
                    <button @click="open=!open"
                            class="flex items-center gap-2 p-1 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <img src="{{ auth()->user()->profile_photo_url }}" alt=""
                             class="w-8 h-8 rounded-full object-cover ring-2 ring-transparent hover:ring-red-500 transition-all">
                    </button>
                    <div x-show="open" x-cloak @click.outside="open=false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute end-0 top-full mt-2 w-52 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50 origin-top-right">
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 mb-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        @if(auth()->user()->canAccessAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Admin Panel
                        </a>
                        @endif
                        <a href="{{ route('profile.show') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile
                        </a>
                        <a href="{{ route('bookmarks', ['locale' => $locale]) }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                            Bookmarks
                        </a>
                        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-2.5 w-full text-start px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}"
                   class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors">
                    {{ $locale === 'dv' ? 'ލޮގިން' : 'Sign In' }}
                </a>
                @endauth

                {{-- Subscribe CTA (mobile only) --}}
                @if(!auth()->user()?->isSubscribed())
                <a href="{{ route('membership.plans', ['locale' => $locale]) }}"
                   class="hidden xs:inline-flex lg:hidden items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors">
                    PRO
                </a>
                @endif

                {{-- Mobile hamburger --}}
                <button @click="$dispatch('toggle-mobile-menu')"
                        class="lg:hidden p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Category Nav (desktop) --}}
        <nav class="hidden lg:flex items-center gap-0.5 border-t border-gray-100 dark:border-gray-800 -mx-4 px-4 overflow-x-auto scrollbar-none">
            <a href="{{ route('home', ['locale' => $locale]) }}"
               class="shrink-0 flex items-center px-3 py-2.5 text-sm font-medium transition-colors border-b-2 {{ request()->routeIs('home') ? 'text-red-600 border-red-600' : 'text-gray-600 dark:text-gray-300 border-transparent hover:text-gray-900 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-600' }}">
                {{ __('news.home') }}
            </a>

            @foreach($categories->where('parent_id', null)->take(9) as $category)
            <div class="relative shrink-0" x-data="{ hover: false }"
                 @mouseenter="hover=true" @mouseleave="hover=false">
                <a href="{{ route('category.show', ['locale' => $locale, 'slug' => $category->getSlugForLocale($locale)]) }}"
                   class="flex items-center gap-1 px-3 py-2.5 text-sm font-medium transition-colors border-b-2 {{ request()->routeIs('category.*') && request()->segment(3) === $category->getSlugForLocale($locale) ? 'text-red-600 border-red-600' : 'text-gray-600 dark:text-gray-300 border-transparent hover:text-gray-900 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-600' }}">
                    {{ $category->getName($locale) }}
                    @if($category->children->count())
                    <svg class="w-3 h-3 transition-transform" :class="hover ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    @endif
                </a>
                @if($category->children->count())
                <div x-show="hover"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-full start-0 w-48 bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 py-2 z-50 mt-0.5">
                    @foreach($category->children as $child)
                    <a href="{{ route('category.show', ['locale' => $locale, 'slug' => $child->getSlugForLocale($locale)]) }}"
                       class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        {{ $child->getName($locale) }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach

            {{-- Subscribe CTA --}}
            @if(!auth()->user()?->isSubscribed())
            <div class="ms-auto ps-4 shrink-0">
                <a href="{{ route('membership.plans', ['locale' => $locale]) }}"
                   class="flex items-center gap-1.5 px-3 py-1.5 mb-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white text-xs font-bold rounded-lg transition-all shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    {{ $locale === 'dv' ? 'ޕްރިމިއަމް' : 'Subscribe' }}
                </a>
            </div>
            @endif
        </nav>
    </div>
</header>
