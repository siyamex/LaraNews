<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email'   => 'required|email|max:255',
            'name'    => 'nullable|string|max:100',
            'list_id' => 'nullable|exists:newsletter_lists,id',
        ]);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $data['email']],
            [
                'name'             => $data['name'] ?? null,
                'token'            => Str::random(40),
                'subscribed_at'    => now(),
                'is_confirmed'     => false,
            ]
        );

        if ($data['list_id'] ?? null) {
            $subscriber->lists()->syncWithoutDetaching([$data['list_id']]);
        }

        return response()->json(['message' => 'Please check your email to confirm your subscription.']);
    }

    public function unsubscribe(Request $request)
    {
        $data = $request->validate(['email' => 'required|email', 'token' => 'required|string']);

        $subscriber = NewsletterSubscriber::where('email', $data['email'])
            ->where('token', $data['token'])
            ->firstOrFail();

        $subscriber->update(['is_confirmed' => false, 'unsubscribed_at' => now()]);

        return response()->json(['message' => 'You have been unsubscribed.']);
    }
}
