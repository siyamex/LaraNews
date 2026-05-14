@php $locale = app()->getLocale(); @endphp
<div class="relative z-10 -mt-16 bg-white dark:bg-gray-950 pt-8 pb-10 px-6 text-center rounded-b-3xl border border-gray-100 dark:border-gray-800 shadow-xl">
    <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
    </div>
    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">
        {{ $locale === 'dv' ? 'ޕްރިމިއަމް ހަބަރު' : 'Premium Article' }}
    </h3>
    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 max-w-md mx-auto">
        {{ $locale === 'dv' ? 'މި ލިޔުން ކިޔުމަށް ޕްރިމިއަމް ސަބްސްކްރިޕްޝަން ބޭނުން ވެއެވެ.' : 'This article is for premium members. Subscribe to read the full story.' }}
    </p>
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('membership.plans', ['locale' => $locale]) }}"
           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            {{ $locale === 'dv' ? 'ސަބްސްކްރައިބ' : 'Subscribe Now' }}
        </a>
        @guest
        <a href="{{ route('login') }}"
           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            {{ $locale === 'dv' ? 'ލޮގިން' : 'Sign In' }}
        </a>
        @endguest
    </div>
    <p class="text-xs text-gray-400 mt-4">
        {{ $locale === 'dv' ? 'ވެލިޑެއިޝަން ނެތް. ކޮންމެ ވަގުތެއްގައި ވެސް ކެންސަލް ކޮށްލެވޭ.' : 'No commitment. Cancel anytime.' }}
    </p>
</div>
