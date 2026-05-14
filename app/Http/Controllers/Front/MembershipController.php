<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\Subscription;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function plans(Request $request, string $locale)
    {
        $plans = MembershipPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $currentSubscription = auth()->check()
            ? auth()->user()->subscriptions()->where('status', 'active')->first()
            : null;

        $seo = [
            'title'   => ($locale === 'dv' ? 'ޕްރިމިއަމް' : 'Premium Membership') . ' — ' . config('app.name'),
            'og_type' => 'website',
        ];

        return view('front.membership.plans', compact('plans', 'currentSubscription', 'seo', 'locale'));
    }

    public function subscribe(Request $request, string $locale)
    {
        $request->validate(['plan_id' => 'required|exists:membership_plans,id']);

        $plan = MembershipPlan::findOrFail($request->plan_id);

        return view('front.membership.checkout', compact('plan', 'locale'));
    }

    public function success(Request $request, string $locale)
    {
        return view('front.membership.success', compact('locale'));
    }

    public function cancelSubscription(Request $request, string $locale)
    {
        $subscription = auth()->user()->subscriptions()->where('status', 'active')->firstOrFail();
        $subscription->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        return redirect()->route('membership.plans', ['locale' => $locale])
            ->with('success', __('news.subscription_cancelled'));
    }

    public function cancel(Request $request, string $locale)
    {
        return $this->cancelSubscription($request, $locale);
    }
}
