<div x-data="{}"
     @monitor-flash.window="setTimeout(() => $wire.set('message', ''), 4000)">

    {{-- Flash Message --}}
    @if($message)
    <div x-show="true" x-transition
         class="mb-6 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2
                {{ $messageType === 'success' ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800' : 'bg-red-50 text-red-700 border border-red-200' }}">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ $message }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- System Info --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                System
            </h3>
            <dl class="space-y-2 text-sm">
                @foreach([
                    'PHP Version'    => $system['php_version'],
                    'Laravel'        => $system['laravel'],
                    'Environment'    => $system['environment'],
                    'Debug Mode'     => $system['debug'] ? '⚠️ ON' : '✅ OFF',
                    'Memory Limit'   => $system['memory_limit'],
                    'Max Upload'     => $system['max_upload'],
                    'Queue Driver'   => $system['queue_driver'],
                    'Cache Driver'   => $system['cache_driver'],
                    'Session Driver' => $system['session_driver'],
                ] as $label => $value)
                <div class="flex justify-between py-1.5 border-b border-gray-50 dark:border-gray-700/50 last:border-0">
                    <dt class="text-gray-500">{{ $label }}</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ $value }}</dd>
                </div>
                @endforeach
            </dl>

            {{-- Disk Usage --}}
            <div class="mt-4">
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Disk Usage</span>
                    <span>{{ $system['disk_free_gb'] }}GB free / {{ $system['disk_total_gb'] }}GB</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $system['disk_pct'] > 85 ? 'bg-red-500' : ($system['disk_pct'] > 70 ? 'bg-yellow-500' : 'bg-green-500') }}"
                         style="width: {{ $system['disk_pct'] }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $system['disk_pct'] }}% used</p>
            </div>
        </div>

        {{-- Queue & Cache --}}
        <div class="space-y-4">
            {{-- Queue Stats --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Queue
                </h3>
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['pending' => ['label' => 'Pending', 'color' => 'yellow'], 'failed' => ['label' => 'Failed', 'color' => 'red'], 'batches' => ['label' => 'Batches', 'color' => 'blue']] as $key => $meta)
                    <div class="text-center p-3 rounded-lg bg-{{ $meta['color'] }}-50 dark:bg-{{ $meta['color'] }}-900/20">
                        <p class="text-2xl font-bold text-{{ $meta['color'] }}-600 dark:text-{{ $meta['color'] }}-400">{{ $queue[$key] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $meta['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Cache Controls --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Cache Controls
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    <button wire:click="clearCache" wire:loading.attr="disabled"
                            class="px-3 py-2 bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-400 text-xs font-medium rounded-lg hover:bg-orange-100 transition-colors border border-orange-200 dark:border-orange-800">
                        <span wire:loading.remove wire:target="clearCache">🗑 Clear All Cache</span>
                        <span wire:loading wire:target="clearCache">Clearing...</span>
                    </button>
                    <button wire:click="clearViews" wire:loading.attr="disabled"
                            class="px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-lg hover:bg-blue-100 transition-colors border border-blue-200 dark:border-blue-800">
                        <span wire:loading.remove wire:target="clearViews">🔄 Rebuild Views</span>
                        <span wire:loading wire:target="clearViews">Rebuilding...</span>
                    </button>
                    <button wire:click="clearConfig" wire:loading.attr="disabled"
                            class="px-3 py-2 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400 text-xs font-medium rounded-lg hover:bg-purple-100 transition-colors border border-purple-200 dark:border-purple-800">
                        <span wire:loading.remove wire:target="clearConfig">⚙️ Clear Config</span>
                        <span wire:loading wire:target="clearConfig">Clearing...</span>
                    </button>
                    <button wire:click="optimizeApp" wire:loading.attr="disabled"
                            class="px-3 py-2 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-xs font-medium rounded-lg hover:bg-green-100 transition-colors border border-green-200 dark:border-green-800">
                        <span wire:loading.remove wire:target="optimizeApp">🚀 Optimize All</span>
                        <span wire:loading wire:target="optimizeApp">Optimizing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Database Tables --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                Database — {{ $db['connection'] }}
            </h3>
            <span class="text-xs text-gray-500">Total: {{ $db['total_mb'] }} MB</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-gray-100 dark:border-gray-700">
                        <th class="pb-2 text-xs font-medium text-gray-500 uppercase">Table</th>
                        <th class="pb-2 text-xs font-medium text-gray-500 uppercase text-right">Rows</th>
                        <th class="pb-2 text-xs font-medium text-gray-500 uppercase text-right">Size (MB)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($db['tables'] as $table)
                    <tr>
                        <td class="py-2 font-mono text-xs text-gray-700 dark:text-gray-300">{{ $table->name }}</td>
                        <td class="py-2 text-right text-xs text-gray-500">{{ number_format($table->rows) }}</td>
                        <td class="py-2 text-right text-xs text-gray-500">{{ $table->size_mb }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
