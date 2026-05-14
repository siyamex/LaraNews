<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdZone;
use Illuminate\Http\Request;

class AdZoneController extends Controller
{
    public function index() { return view('admin.ad-zones.index', ['zones' => AdZone::withCount('ads')->get()]); }
    public function create() { return view('admin.ad-zones.create'); }
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string', 'placement' => 'required|string']);
        AdZone::create($request->except('_token') + ['is_active' => true]);
        return redirect()->route('admin.ad-zones.index');
    }
    public function edit(AdZone $adZone) { return view('admin.ad-zones.edit', compact('adZone')); }
    public function update(Request $request, AdZone $adZone) { $adZone->update($request->except('_token', '_method')); return redirect()->route('admin.ad-zones.index'); }
    public function destroy(AdZone $adZone) { $adZone->delete(); return redirect()->route('admin.ad-zones.index'); }
}
