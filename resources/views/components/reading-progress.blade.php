{{-- Reading progress bar – fixed at top of viewport --}}
<div x-data="{
        progress: 0,
        update() {
            const el = document.getElementById('article-body');
            if (el) {
                const top    = el.getBoundingClientRect().top + window.scrollY;
                const bot    = el.getBoundingClientRect().bottom + window.scrollY;
                const h      = bot - top;
                const scrolled = window.scrollY - top;
                this.progress = Math.min(100, Math.max(0, (scrolled / h) * 100));
            } else {
                const s = document.documentElement;
                this.progress = Math.min(100, (s.scrollTop / (s.scrollHeight - s.clientHeight)) * 100) || 0;
            }
        }
     }"
     @scroll.window.throttle.50ms="update()"
     x-init="update()"
     class="fixed top-0 inset-x-0 z-50 h-0.5 bg-transparent pointer-events-none">
    <div class="h-full bg-gradient-to-r from-red-500 via-red-600 to-red-500"
         :style="'width: ' + progress + '%'"
         x-show="progress > 0 && progress < 100"
         style="display:none;"></div>
</div>
