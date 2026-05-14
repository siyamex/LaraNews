<?php

namespace App\Livewire\Front;

use App\Models\NewsletterList;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Str;
use Livewire\Component;

class NewsletterSubscribe extends Component
{
    public string $email = '';
    public bool $subscribed = false;
    public string $message = '';

    protected $rules = ['email' => 'required|email|max:255'];

    public function subscribe(): void
    {
        $this->validate();

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $this->email],
            ['token' => Str::uuid(), 'status' => 'active']
        );

        if ($subscriber->status === 'unsubscribed') {
            $subscriber->update(['status' => 'active']);
        }

        $defaultList = NewsletterList::where('is_default', true)->first();
        $defaultList?->subscribers()->syncWithoutDetaching([$subscriber->id]);

        $this->subscribed = true;
        $this->message    = __('news.newsletter_subscribed');
        $this->email      = '';
    }

    public function render()
    {
        return view('livewire.front.newsletter-subscribe');
    }
}
