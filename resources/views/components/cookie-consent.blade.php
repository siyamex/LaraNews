@php $locale = app()->getLocale(); @endphp

<div x-data="{
        show: localStorage.getItem('cc_decision') === null,
        detail: false,
        analytics: true,
        marketing: false,
        preferences: true,
        accept() {
            this.save(true, true, true);
        },
        reject() {
            this.save(false, false, true);
        },
        saveCustom() {
            this.save(this.analytics, this.marketing, this.preferences);
        },
        save(an, mk, pr) {
            localStorage.setItem('cc_decision', JSON.stringify({ analytics: an, marketing: mk, preferences: pr, ts: Date.now() }));
            document.cookie = 'cc_analytics=' + (an ? '1' : '0') + ';max-age=31536000;path=/;SameSite=Lax';
            document.cookie = 'cc_marketing=' + (mk ? '1' : '0') + ';max-age=31536000;path=/;SameSite=Lax';
            fetch('/{{ $locale }}/privacy/consent', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ analytics: an, marketing: mk, preferences: pr })
            }).catch(() => {});
            this.show = false;
        }
    }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     class="fixed bottom-4 inset-x-4 sm:inset-x-auto sm:right-4 sm:left-auto sm:w-96 z-50 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden"
     style="display:none;">

    {{-- Main Banner --}}
    <div x-show="!detail" class="p-5">
        <div class="flex items-start gap-3 mb-3">
            <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Cookie Preferences</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    @if($locale === 'dv')
                        ތިމާގެ ތަޖުރިބާ ރަނގަޅު ކުރުމަށް ކުކީ ބޭނުން ކުރަމެވެ.
                    @else
                        We use cookies to improve your experience.
                        <a href="{{ route('page.show', ['locale' => $locale, 'slug' => 'privacy-policy']) }}" class="text-red-600 hover:underline">Privacy Policy</a>
                    @endif
                </p>
            </div>
        </div>

        <div class="flex gap-2">
            <button @click="accept()"
                    class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors">
                Accept All
            </button>
            <button @click="reject()"
                    class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg transition-colors">
                Reject All
            </button>
            <button @click="detail=true"
                    class="px-3 py-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-xs border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                Manage
            </button>
        </div>
    </div>

    {{-- Detail Panel --}}
    <div x-show="detail" class="p-5">
        <button @click="detail=false" class="flex items-center gap-1 text-xs text-gray-500 hover:text-gray-700 mb-4">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back
        </button>
        <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-3">Manage Cookie Preferences</h3>

        <div class="space-y-3 mb-4">
            {{-- Essential (always on) --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-900 dark:text-white">Essential</p>
                    <p class="text-xs text-gray-400">Required for the site to work</p>
                </div>
                <span class="text-xs text-green-600 font-medium">Always on</span>
            </div>
            @foreach([
                ['key' => 'analytics',   'label' => 'Analytics',   'desc' => 'Page views, session data'],
                ['key' => 'marketing',   'label' => 'Marketing',   'desc' => 'Personalized ads'],
                ['key' => 'preferences','label' => 'Preferences', 'desc' => 'Theme, language, font'],
            ] as $c)
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-900 dark:text-white">{{ $c['label'] }}</p>
                    <p class="text-xs text-gray-400">{{ $c['desc'] }}</p>
                </div>
                <button @click="{{ $c['key'] }} = !{{ $c['key'] }}"
                        :class="{{ $c['key'] }} ? 'bg-red-600' : 'bg-gray-200 dark:bg-gray-700'"
                        class="relative w-9 h-5 rounded-full transition-colors">
                    <span :class="{{ $c['key'] }} ? 'translate-x-4' : 'translate-x-0.5'"
                          class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"></span>
                </button>
            </div>
            @endforeach
        </div>

        <button @click="saveCustom()"
                class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors">
            Save My Choices
        </button>
    </div>
</div>
