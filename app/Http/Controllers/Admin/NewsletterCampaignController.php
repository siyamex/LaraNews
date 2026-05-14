<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DispatchCampaign;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterList;
use Illuminate\Http\Request;

class NewsletterCampaignController extends Controller
{
    public function index()
    {
        return view('admin.newsletter.campaigns', [
            'campaigns' => NewsletterCampaign::with('list')->latest()->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.newsletter.campaign-create', [
            'lists' => NewsletterList::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        NewsletterCampaign::create([
            'subject'            => $request->subject,
            'preheader'          => $request->preheader,
            'content'            => $request->content,
            'newsletter_list_id' => $request->list_id ?: null,
            'scheduled_at'       => $request->scheduled_at ?: null,
            'user_id'            => auth()->id(),
            'status'             => $request->scheduled_at ? 'scheduled' : 'draft',
        ]);

        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('success', 'Campaign saved.');
    }

    public function edit(NewsletterCampaign $campaign)
    {
        return view('admin.newsletter.campaign-edit', compact('campaign'));
    }

    public function update(Request $request, NewsletterCampaign $campaign)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $campaign->update([
            'subject'      => $request->subject,
            'preheader'    => $request->preheader,
            'content'      => $request->content,
            'scheduled_at' => $request->scheduled_at ?: null,
            'status'       => $request->scheduled_at ? 'scheduled' : 'draft',
        ]);

        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('success', 'Campaign updated.');
    }

    public function send(NewsletterCampaign $campaign)
    {
        if (! in_array($campaign->status, ['draft', 'scheduled'])) {
            return back()->with('error', 'Campaign cannot be sent in its current state.');
        }

        DispatchCampaign::dispatch($campaign);

        return back()->with('success', 'Campaign queued for sending.');
    }

    public function destroy(NewsletterCampaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('success', 'Campaign deleted.');
    }
}
