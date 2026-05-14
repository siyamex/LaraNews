<div x-data="postEditor()" class="space-y-6">
    {{-- Saved notification --}}
    @if($this->aiMessage)
    <div class="fixed top-4 end-4 z-50 bg-emerald-500 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium animate-slide-up"
         x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        {{ $this->aiMessage }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Column --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Locale tabs --}}
            <div class="flex gap-1 bg-gray-100 dark:bg-gray-800 rounded-xl p-1">
                @foreach(['dv' => 'ދިވެހި', 'en' => 'English'] as $loc => $label)
                <button wire:click="$set('activeLocale', '{{ $loc }}')"
                        class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all {{ $activeLocale === $loc ? 'bg-white dark:bg-gray-700 shadow text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ $label }}
                </button>
                @endforeach
            </div>

            @foreach(['dv', 'en'] as $loc)
            <div class="{{ $activeLocale === $loc ? 'block' : 'hidden' }} space-y-4" wire:key="locale-{{ $loc }}">

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        {{ $loc === 'dv' ? 'ސުރުހީ' : 'Title' }} <span class="text-red-500">*</span>
                    </label>
                    <input wire:model.blur="translations.{{ $loc }}.title" type="text"
                           class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-xl font-bold focus:ring-red-500"
                           placeholder="{{ $loc === 'dv' ? 'ސުރުހީ ލިޔޭ...' : 'Enter title...' }}"
                           dir="{{ $loc === 'dv' ? 'rtl' : 'ltr' }}">
                    @error("translations.{$loc}.title") <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Slug</label>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400">/{{ $loc }}/news/</span>
                        <input wire:model="translations.{{ $loc }}.slug" type="text"
                               class="flex-1 text-sm rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-red-500">
                    </div>
                </div>

                {{-- Excerpt --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            {{ $loc === 'dv' ? 'ތަފްސީލް' : 'Excerpt' }}
                        </label>
                        <button wire:click="aiGenerateSummary" wire:loading.attr="disabled"
                                class="text-xs flex items-center gap-1 text-purple-600 dark:text-purple-400 hover:text-purple-700 disabled:opacity-50">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.001z"/></svg>
                            AI Generate
                        </button>
                    </div>
                    <textarea wire:model="translations.{{ $loc }}.excerpt" rows="3"
                              class="w-full text-sm rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-red-500 resize-none"
                              dir="{{ $loc === 'dv' ? 'rtl' : 'ltr' }}"
                              placeholder="{{ $loc === 'dv' ? 'ތަފްސީލް ލިޔޭ...' : 'Write excerpt...' }}"></textarea>
                </div>

                {{-- Content --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        {{ $loc === 'dv' ? 'ލިޔުން' : 'Content' }}
                    </label>
                    <textarea wire:model="translations.{{ $loc }}.content" rows="20"
                              id="editor-{{ $loc }}"
                              class="w-full text-sm rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-red-500 font-mono"
                              dir="{{ $loc === 'dv' ? 'rtl' : 'ltr' }}"
                              placeholder="{{ $loc === 'dv' ? 'ލިޔުން ލިޔޭ...' : 'Write content...' }}"></textarea>
                </div>

                {{-- SEO Meta --}}
                <details class="bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
                    <summary class="flex items-center justify-between px-4 py-3 cursor-pointer font-semibold text-sm">
                        SEO Meta
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="aiGenerateSeoMeta"
                                    class="text-xs text-purple-600 font-normal hover:text-purple-700">AI Fill</button>
                        </div>
                    </summary>
                    <div class="px-4 pb-4 space-y-3">
                        <input wire:model="translations.{{ $loc }}.meta_title" type="text"
                               class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                               placeholder="Meta title">
                        <textarea wire:model="translations.{{ $loc }}.meta_description" rows="2"
                                  class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white resize-none"
                                  placeholder="Meta description"></textarea>
                    </div>
                </details>
            </div>
            @endforeach
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">

            {{-- Publish box --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 space-y-4">
                <h3 class="font-black text-gray-900 dark:text-white">Publish</h3>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select wire:model="status" class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="draft">Draft</option>
                        <option value="pending">Pending Review</option>
                        <option value="published">Published</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                @if($status === 'scheduled')
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Publish Date</label>
                    <input wire:model="publishedAt" type="datetime-local"
                           class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                </div>
                @endif

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Post Type</label>
                    <select wire:model="type" class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        @foreach(['article' => 'Article', 'gallery' => 'Gallery', 'video' => 'Video', 'audio' => 'Audio', 'poll' => 'Poll', 'trivia_quiz' => 'Trivia Quiz', 'personality_quiz' => 'Personality Quiz', 'recipe' => 'Recipe', 'event' => 'Event', 'sorted_list' => 'Sorted List', 'live_blog' => 'Live Blog'] as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3">
                    <button wire:click="save('draft')" wire:loading.attr="disabled"
                            class="flex-1 py-2 text-sm font-semibold border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Save Draft
                    </button>
                    <button wire:click="save('published')" wire:loading.attr="disabled"
                            class="flex-1 py-2 text-sm font-semibold bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">Publish</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </div>

            {{-- Category --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5">
                <h3 class="font-black text-gray-900 dark:text-white mb-3">Category</h3>
                <select wire:model="categoryId" class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">— No Category —</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->getName('en') }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Featured Image --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5">
                <h3 class="font-black text-gray-900 dark:text-white mb-3">Featured Image</h3>
                @if($featuredImage)
                <div class="relative mb-3">
                    <img src="{{ asset('storage/' . $featuredImage) }}" class="w-full h-40 object-cover rounded-xl">
                    <button wire:click="$set('featuredImage', null)" class="absolute top-2 end-2 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-700">✕</button>
                </div>
                @endif
                <input wire:model="uploadedImage" type="file" accept="image/*"
                       class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-red-600 file:text-white file:text-xs file:font-semibold hover:file:bg-red-700">
                @if($uploadedImage)
                <button wire:click="uploadFeaturedImage" class="mt-2 w-full py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">Upload</button>
                @endif
                <input wire:model="featuredImageCaption" type="text" placeholder="Image caption"
                       class="mt-2 w-full text-xs rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            {{-- Tags --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5">
                <h3 class="font-black text-gray-900 dark:text-white mb-3">Tags</h3>
                <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto">
                    @foreach($tags as $tag)
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input wire:model="tagIds" type="checkbox" value="{{ $tag->id }}" class="rounded text-red-600">
                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ $tag->getName('en') }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Options --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 space-y-3">
                <h3 class="font-black text-gray-900 dark:text-white">Options</h3>
                @foreach([
                    ['isFeatured', 'Featured Story'],
                    ['isBreaking', 'Breaking News'],
                    ['isPremium', 'Premium (Paywall)'],
                    ['allowComments', 'Allow Comments'],
                ] as [$field, $label])
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input wire:model="{{ $field }}" type="checkbox" class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-200 dark:bg-gray-600 rounded-full peer-checked:bg-red-600 transition-colors"></div>
                        <div class="absolute top-0.5 start-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4 rtl:peer-checked:-translate-x-4"></div>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                </label>
                @endforeach

                @if($isPremium)
                <div>
                    <label class="text-xs font-medium text-gray-500">Paywall Type</label>
                    <select wire:model="paywallType" class="w-full mt-1 text-sm rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="hard">Hard Paywall</option>
                        <option value="soft">Soft Paywall</option>
                        <option value="fade">Fade Paywall</option>
                    </select>
                    <div class="flex items-center gap-2 mt-2">
                        <label class="text-xs text-gray-500">Free paragraphs:</label>
                        <input wire:model="freeParagraphs" type="number" min="0" max="20"
                               class="w-16 text-xs rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    </div>
                </div>
                @endif
            </div>

            {{-- Source --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 space-y-3">
                <h3 class="font-black text-gray-900 dark:text-white">Source</h3>
                <input wire:model="sourceName" type="text" placeholder="Source name"
                       class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                <input wire:model="sourceUrl" type="url" placeholder="Source URL"
                       class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            </div>
        </div>
    </div>
</div>

@script
<script>
    function postEditor() {
        return {
            init() {
                // Hook for future rich text editor integration (TipTap, etc.)
            }
        };
    }
</script>
@endscript
