<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
*/

// Send newsletter campaigns that are due
Schedule::command('newsletter:send-scheduled')->everyFiveMinutes();

// Import all active RSS feeds every hour
Schedule::command('rss:import-all')->hourly();

// Prune stale activity logs (keep 90 days)
Schedule::command('activitylog:clean --days=90')->weekly();

// Telescope pruning (if installed)
Schedule::command('telescope:prune --hours=48')->daily();

// Horizon snapshot for metrics
Schedule::command('horizon:snapshot')->everyFiveMinutes();
