<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function stripeWebhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            Log::error('Stripe webhook signature failed: ' . $e->getMessage());
            return response()->json(['error' => 'Signature verification failed.'], 400);
        }

        match ($event->type) {
            'payment_intent.succeeded'       => $this->handlePaymentSucceeded($event->data->object),
            'customer.subscription.deleted'  => $this->handleSubscriptionCancelled($event->data->object),
            default                          => null,
        };

        return response()->json(['status' => 'ok']);
    }

    private function handlePaymentSucceeded(object $paymentIntent): void
    {
        Payment::where('provider_payment_id', $paymentIntent->id)
            ->update(['status' => 'completed', 'paid_at' => now()]);
    }

    private function handleSubscriptionCancelled(object $subscription): void
    {
        Subscription::where('stripe_id', $subscription->id)
            ->update(['status' => 'cancelled', 'ends_at' => now()]);
    }
}
