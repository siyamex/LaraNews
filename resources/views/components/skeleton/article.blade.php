{{-- Skeleton for full article view --}}
<div class="animate-pulse space-y-6">
    {{-- Category + title --}}
    <div class="space-y-3">
        <div class="h-5 w-24 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
    </div>
    {{-- Meta --}}
    <div class="flex items-center gap-4">
        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
        <div class="space-y-1.5">
            <div class="h-3.5 bg-gray-200 dark:bg-gray-700 rounded w-32"></div>
            <div class="h-3 bg-gray-100 dark:bg-gray-600 rounded w-24"></div>
        </div>
    </div>
    {{-- Hero image --}}
    <div class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-2xl"></div>
    {{-- Body paragraphs --}}
    @for($i = 0; $i < 6; $i++)
    <div class="space-y-2">
        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded {{ $i % 2 === 0 ? 'w-5/6' : 'w-4/5' }}"></div>
    </div>
    @endfor
</div>
