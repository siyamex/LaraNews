<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribers(Request $request)
    {
        $subscribers = NewsletterSubscriber::with('lists')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(25);
        return view('admin.newsletter.subscribers', compact('subscribers'));
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::where('status', 'active')->get(['email', 'created_at']);
        $csv = "email,subscribed_at\n" . $subscribers->map(fn($s) => "{$s->email},{$s->created_at}")->implode("\n");
        return response($csv, 200, ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="subscribers.csv"']);
    }
}
