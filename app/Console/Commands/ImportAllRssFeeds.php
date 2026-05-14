<?php

namespace App\Console\Commands;

use App\Jobs\ImportRssFeed;
use App\Models\RssSource;
use Illuminate\Console\Command;

class ImportAllRssFeeds extends Command
{
    protected $signature   = 'rss:import-all';
    protected $description = 'Dispatch import jobs for all active RSS sources';

    public function handle(): void
    {
        $sources = RssSource::active()->get();

        foreach ($sources as $source) {
            ImportRssFeed::dispatch($source);
            $this->info("Queued: {$source->name}");
        }

        $this->info("Dispatched {$sources->count()} RSS import jobs.");
    }
}
