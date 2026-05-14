<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RssSource;
use App\Services\RssImportService;
use Illuminate\Http\Request;

class RssSourceController extends Controller
{
    public function __construct(private readonly RssImportService $rssImportService) {}

    public function index() { return view('admin.rss.index', ['sources' => RssSource::withCount('items')->latest()->paginate(20)]); }
    public function create() { return view('admin.rss.create'); }
    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'url' => 'required|url', 'locale' => 'required|in:dv,en']);
        RssSource::create($request->except('_token') + ['is_active' => true]);
        return redirect()->route('admin.rss-sources.index');
    }
    public function edit(RssSource $rssSource) { return view('admin.rss.edit', compact('rssSource')); }
    public function update(Request $request, RssSource $rssSource) { $rssSource->update($request->except('_token', '_method')); return redirect()->route('admin.rss-sources.index'); }
    public function destroy(RssSource $rssSource) { $rssSource->delete(); return redirect()->route('admin.rss-sources.index'); }
    public function importNow(RssSource $rssSource)
    {
        $count = $this->rssImportService->importSource($rssSource);
        return response()->json(['message' => "Imported {$count} new items."]);
    }
}
