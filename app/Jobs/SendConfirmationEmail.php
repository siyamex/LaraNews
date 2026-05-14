<?php

namespace App\Jobs;

use App\Mail\NewsletterConfirmationMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public NewsletterSubscriber $subscriber) {}

    public function handle(): void
    {
        Mail::to($this->subscriber->email, $this->subscriber->name)
            ->send(new NewsletterConfirmationMail($this->subscriber));
    }
}
