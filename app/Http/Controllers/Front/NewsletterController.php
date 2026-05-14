<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Jobs\SendConfirmationEmail;
use App\Models\NewsletterList;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $request->email],
            ['token' => Str::uuid(), 'status' => 'pending']
        );

        if ($subscriber->status === 'unsubscribed') {
            $subscriber->update(['status' => 'active']);
        }

        // Attach to default list
        $defaultList = NewsletterList::where('is_active', true)->first();
        if ($defaultList) {
            $defaultList->subscribers()->syncWithoutDetaching([$subscriber->id]);
        }

        // Send confirmation email only to new/pending subscribers
        if ($subscriber->wasRecentlyCreated || $subscriber->status === 'pending') {
            SendConfirmationEmail::dispatch($subscriber);
        }

        return response()->json(['message' => __('news.newsletter_subscribed')]);
    }

    public function unsubscribe(Request $request, string $token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->firstOrFail();
        $subscriber->update(['status' => 'unsubscribed']);

        return view('front.newsletter.unsubscribed');
    }

    public function confirm(Request $request, string $token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        $subscriber->update(['status' => 'active', 'confirmed_at' => now()]);

        return view('front.newsletter.confirmed');
    }
}
