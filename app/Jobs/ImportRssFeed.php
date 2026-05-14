<?php

namespace App\Jobs;

use App\Models\RssSource;
use App\Services\RssImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportRssFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 2;
    public int $timeout = 120;

    public function __construct(public RssSource $source) {}

    public function handle(RssImportService $service): void
    {
        if (! $this->source->is_active) {
            return;
        }

        try {
            $count = $service->import($this->source);
            Log::info("RSS import: {$this->source->name} — {$count} items imported.");
        } catch (\Throwable $e) {
            Log::error("RSS import failed [{$this->source->name}]: " . $e->getMessage());
            throw $e;
        }
    }
}
