<div>
    @if($subscribed)
    <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-medium">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ $message }}
    </div>
    @else
    <form wire:submit="subscribe" class="flex gap-2">
        <div class="flex-1 relative">
            <input wire:model="email" type="email"
                   placeholder="{{ app()->getLocale() === 'dv' ? 'ތިބާގެ އީމެއިލް' : 'Your email address' }}"
                   class="w-full px-4 py-2.5 rounded-lg border border-white/20 bg-white/10 text-white placeholder-white/60 focus:ring-2 focus:ring-white/50 focus:border-transparent text-sm">
        </div>
        <button type="submit" wire:loading.attr="disabled"
                class="px-5 py-2.5 bg-white text-emerald-700 font-semibold rounded-lg hover:bg-emerald-50 transition-colors text-sm shrink-0 disabled:opacity-70">
            <span wire:loading.remove>{{ app()->getLocale() === 'dv' ? 'ސަބްސްކްރައިބ' : 'Subscribe' }}</span>
            <span wire:loading>...</span>
        </button>
    </form>
    @error('email') <p class="text-xs text-red-300 mt-1">{{ $message }}</p> @enderror
    @endif
</div>
