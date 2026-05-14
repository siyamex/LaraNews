@php
    $locale = app()->getLocale();
    $settings = \App\Models\Setting::getGroup('general');
    $socialSettings = \App\Models\Setting::getGroup('social');
@endphp
<footer class="bg-gray-900 dark:bg-black text-gray-300 mt-16">

    {{-- Newsletter Section --}}
    <div class="bg-red-700 py-10">
        <div class="container mx-auto px-4">
            <livewire:front.newsletter-subscribe />
        </div>
    </div>

    {{-- Main Footer --}}
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            {{-- Logo & About --}}
            <div class="lg:col-span-1">
                <a href="{{ route('home', ['locale' => $locale]) }}" class="block mb-4">
                    <img src="{{ asset('images/logo-white.png') }}" alt="{{ config('app.name') }}" class="h-10"
                         onerror="this.style.display='none'">
                    <span class="text-xl font-bold text-white">{{ config('app.name') }}</span>
                </a>
                <p class="text-sm text-gray-400 leading-relaxed">
                    {{ $settings['site_description'] ?? ($locale === 'dv' ? 'ދިވެހިންގެ ހަބަރު. ތިލަ ފަހި ބަހުން.' : 'Maldivian news in Dhivehi and English.') }}
                </p>

                {{-- Social Links --}}
                <div class="flex items-center gap-3 mt-5">
                    @if($fbUrl = $socialSettings['facebook_url'] ?? null)
                    <a href="{{ $fbUrl }}" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-800 hover:bg-blue-600 text-gray-400 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif
                    @if($twitterUrl = $socialSettings['twitter_url'] ?? null)
                    <a href="{{ $twitterUrl }}" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-800 hover:bg-black text-gray-400 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    @endif
                    @if($igUrl = $socialSettings['instagram_url'] ?? null)
                    <a href="{{ $igUrl }}" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-800 hover:bg-gradient-to-r hover:from-purple-600 hover:to-pink-500 text-gray-400 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    @endif
                    <a href="{{ route('feed') }}" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-800 hover:bg-orange-500 text-gray-400 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6.18 15.64a2.18 2.18 0 0 1 2.18 2.18C8.36 19.01 7.38 20 6.18 20C4.98 20 4 19.01 4 17.82a2.18 2.18 0 0 1 2.18-2.18M4 4.44A15.56 15.56 0 0 1 19.56 20h-2.83A12.73 12.73 0 0 0 4 7.27V4.44m0 5.66a9.9 9.9 0 0 1 9.9 9.9h-2.83A7.07 7.07 0 0 0 4 12.93V10.1z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">
                    {{ $locale === 'dv' ? 'ލިންކްތައް' : 'Quick Links' }}
                </h3>
                <ul class="space-y-2.5">
                    @foreach(\App\Models\Category::active()->forMenu()->with('translations')->take(6)->get() as $cat)
                    <li>
                        <a href="{{ route('category.show', ['locale' => $locale, 'slug' => $cat->getSlugForLocale($locale)]) }}"
                           class="text-sm text-gray-400 hover:text-white transition-colors">
                            {{ $cat->getName($locale) }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Info --}}
            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">
                    {{ $locale === 'dv' ? 'ތަފްސީލް' : 'Information' }}
                </h3>
                <ul class="space-y-2.5">
                    @foreach(\App\Models\Page::where('status','published')->where('show_in_menu',true)->get() as $page)
                    @php $pt = $page->translations->where('locale',$locale)->first(); @endphp
                    @if($pt)
                    <li><a href="{{ route('page.show', ['locale' => $locale, 'slug' => $pt->slug]) }}" class="text-sm text-gray-400 hover:text-white transition-colors">{{ $pt->title }}</a></li>
                    @endif
                    @endforeach
                    <li><a href="{{ route('membership.plans', ['locale' => $locale]) }}" class="text-sm text-gray-400 hover:text-white transition-colors">{{ $locale === 'dv' ? 'ސަބްސްކްރިޕްޝަން' : 'Subscribe' }}</a></li>
                </ul>
            </div>

            {{-- App Download --}}
            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">
                    {{ $locale === 'dv' ? 'ޑައުންލޯޑް' : 'Get the App' }}
                </h3>
                <p class="text-sm text-gray-400 mb-4">
                    {{ $locale === 'dv' ? 'ހަބަރު ކިޔާ ތިޔަ ފޯނަށް ލިބިދޭ' : 'Read news on your phone' }}
                </p>
                <div class="space-y-2">
                    <a href="#" class="flex items-center gap-3 bg-gray-800 hover:bg-gray-700 text-white px-4 py-2.5 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                        <div class="text-start">
                            <div class="text-xs text-gray-400">Download on the</div>
                            <div class="text-sm font-semibold">App Store</div>
                        </div>
                    </a>
                    <a href="#" class="flex items-center gap-3 bg-gray-800 hover:bg-gray-700 text-white px-4 py-2.5 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3.18 23.76c.27.15.59.19.92.1l12.25-7.07-2.66-2.66-10.51 9.63zm15.31-8.85L5.8.74C5.53.57 5.22.51 4.9.6L16.47 12.17l2.02 2.74zM20.5 10.5L17.36 8.7 14.53 11.5l2.83 2.83 3.14-1.8a1.5 1.5 0 000-2.03zM3.22.56c-.3.18-.48.5-.48.85v21.18c0 .35.18.67.48.85L15.47 12 3.22.56z"/></svg>
                        <div class="text-start">
                            <div class="text-xs text-gray-400">Get it on</div>
                            <div class="text-sm font-semibold">Google Play</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-gray-800 py-5">
        <div class="container mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ $locale === 'dv' ? 'ހުރިހާ ހައްގެއް ލިބިގެންވޭ.' : 'All rights reserved.' }}</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('page.show', ['locale' => $locale, 'slug' => 'privacy-policy']) }}" class="hover:text-gray-300 transition-colors">Privacy</a>
                <a href="{{ route('page.show', ['locale' => $locale, 'slug' => 'terms']) }}" class="hover:text-gray-300 transition-colors">Terms</a>
                <a href="{{ route('feed') }}" class="hover:text-gray-300 transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M6.18 15.64a2.18 2.18 0 0 1 2.18 2.18C8.36 19.01 7.38 20 6.18 20C4.98 20 4 19.01 4 17.82a2.18 2.18 0 0 1 2.18-2.18M4 4.44A15.56 15.56 0 0 1 19.56 20h-2.83A12.73 12.73 0 0 0 4 7.27V4.44m0 5.66a9.9 9.9 0 0 1 9.9 9.9h-2.83A7.07 7.07 0 0 0 4 12.93V10.1z"/></svg>
                    RSS
                </a>
            </div>
        </div>
    </div>
</footer>
