<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function index() { return view('admin.membership.subscriptions', ['subscriptions' => Subscription::with(['user', 'plan'])->latest()->paginate(25)]); }
    public function show(Subscription $subscription) { return view('admin.membership.subscription', compact('subscription')); }
    public function destroy(Subscription $subscription) { $subscription->update(['status' => 'cancelled']); return redirect()->route('admin.subscriptions.index'); }
}
