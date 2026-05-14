<?php

namespace App\Jobs;

use App\Mail\CampaignMail;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCampaignEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public NewsletterCampaign $campaign,
        public NewsletterSubscriber $subscriber
    ) {}

    public function handle(): void
    {
        if ($this->subscriber->status !== 'subscribed') {
            return;
        }

        Mail::to($this->subscriber->email, $this->subscriber->name)
            ->send(new CampaignMail($this->campaign, $this->subscriber));

        $this->campaign->increment('recipients_count');
    }

    public function failed(\Throwable $e): void
    {
        $this->campaign->increment('bounces_count');
    }
}
