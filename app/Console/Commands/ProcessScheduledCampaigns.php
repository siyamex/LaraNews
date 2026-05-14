<?php

namespace App\Console\Commands;

use App\Jobs\DispatchCampaign;
use App\Models\NewsletterCampaign;
use Illuminate\Console\Command;

class ProcessScheduledCampaigns extends Command
{
    protected $signature   = 'newsletter:send-scheduled';
    protected $description = 'Dispatch newsletter campaigns that are due to be sent';

    public function handle(): void
    {
        $campaigns = NewsletterCampaign::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($campaigns as $campaign) {
            DispatchCampaign::dispatch($campaign);
            $this->info("Dispatched campaign #{$campaign->id}: {$campaign->subject}");
        }

        if ($campaigns->isEmpty()) {
            $this->line('No scheduled campaigns due.');
        }
    }
}
