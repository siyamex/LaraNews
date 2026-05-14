<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdZone;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index()
    {
        $ads   = Ad::with('zone')->latest()->paginate(20);
        $zones = AdZone::all();
        return view('admin.ads.index', compact('ads', 'zones'));
    }

    public function create()
    {
        $zones = AdZone::where('is_active', true)->get();
        return view('admin.ads.create', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ad_zone_id'   => 'required|exists:ad_zones,id',
            'name'         => 'required|string|max:255',
            'type'         => 'required|in:image,html,adsense,amp',
        ]);

        Ad::create($request->validated() + ['is_active' => $request->boolean('is_active', true)]);

        return redirect()->route('admin.ads.index')->with('success', 'Ad created.');
    }

    public function edit(Ad $ad)
    {
        $zones = AdZone::where('is_active', true)->get();
        return view('admin.ads.edit', compact('ad', 'zones'));
    }

    public function update(Request $request, Ad $ad)
    {
        $ad->update($request->except('_token', '_method'));
        return redirect()->route('admin.ads.index')->with('success', 'Ad updated.');
    }

    public function destroy(Ad $ad)
    {
        $ad->delete();
        return redirect()->route('admin.ads.index')->with('success', 'Ad deleted.');
    }
}
