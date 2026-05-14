<?php

namespace App\Jobs;

use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public NewsletterCampaign $campaign) {}

    public function handle(): void
    {
        $this->campaign->update(['status' => 'sending', 'sent_at' => now()]);

        $query = NewsletterSubscriber::where('status', 'subscribed');

        if ($this->campaign->newsletter_list_id) {
            $query->whereHas('lists', fn($q) => $q->where('newsletter_lists.id', $this->campaign->newsletter_list_id));
        }

        $query->chunkById(100, function ($subscribers) {
            foreach ($subscribers as $subscriber) {
                SendCampaignEmail::dispatch($this->campaign, $subscriber)
                    ->onQueue('emails');
            }
        });

        $this->campaign->update(['status' => 'sent']);
    }
}
