<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ServerMonitor extends Component
{
    public string $message = '';
    public string $messageType = 'success';

    public function clearCache(): void
    {
        Cache::flush();
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        $this->flash('Application cache cleared successfully.', 'success');
    }

    public function clearViews(): void
    {
        Artisan::call('view:clear');
        Artisan::call('view:cache');
        $this->flash('View cache rebuilt.', 'success');
    }

    public function clearConfig(): void
    {
        Artisan::call('config:clear');
        $this->flash('Config cache cleared.', 'success');
    }

    public function optimizeApp(): void
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        $this->flash('Application optimized.', 'success');
    }

    private function flash(string $msg, string $type): void
    {
        $this->message     = $msg;
        $this->messageType = $type;
        $this->dispatch('monitor-flash');
    }

    private function systemInfo(): array
    {
        $diskFree  = disk_free_space(base_path());
        $diskTotal = disk_total_space(base_path());

        return [
            'php_version'    => PHP_VERSION,
            'laravel'        => app()->version(),
            'environment'    => app()->environment(),
            'debug'          => config('app.debug'),
            'disk_free_gb'   => round($diskFree / 1073741824, 2),
            'disk_total_gb'  => round($diskTotal / 1073741824, 2),
            'disk_pct'       => round((1 - $diskFree / $diskTotal) * 100),
            'memory_limit'   => ini_get('memory_limit'),
            'max_upload'     => ini_get('upload_max_filesize'),
            'queue_driver'   => config('queue.default'),
            'cache_driver'   => config('cache.default'),
            'session_driver' => config('session.driver'),
        ];
    }

    private function dbStats(): array
    {
        try {
            $tables = DB::select("SELECT table_name AS name,
                ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb,
                table_rows AS rows
                FROM information_schema.tables
                WHERE table_schema = DATABASE()
                ORDER BY (data_length + index_length) DESC
                LIMIT 10");

            $totalSize = DB::selectOne("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS total FROM information_schema.tables WHERE table_schema = DATABASE()");

            return [
                'tables'     => $tables,
                'total_mb'   => $totalSize?->total ?? 0,
                'connection' => config('database.default'),
            ];
        } catch (\Throwable) {
            return ['tables' => [], 'total_mb' => 0, 'connection' => config('database.default')];
        }
    }

    private function queueStats(): array
    {
        try {
            $pending  = DB::table('jobs')->count();
            $failed   = DB::table('failed_jobs')->count();
            $batches  = DB::table('job_batches')->count();
            return compact('pending', 'failed', 'batches');
        } catch (\Throwable) {
            return ['pending' => 0, 'failed' => 0, 'batches' => 0];
        }
    }

    public function render()
    {
        return view('livewire.admin.server-monitor', [
            'system' => $this->systemInfo(),
            'db'     => $this->dbStats(),
            'queue'  => $this->queueStats(),
        ]);
    }
}
