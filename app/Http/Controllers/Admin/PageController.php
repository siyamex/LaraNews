<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() { return view('admin.pages.index', ['pages' => Page::with('translations')->paginate(20)]); }
    public function create() { return view('admin.pages.create'); }

    public function store(Request $request)
    {
        $page = Page::create(['slug' => $request->slug, 'template' => $request->template ?? 'default', 'status' => $request->status ?? 'published', 'user_id' => auth()->id()]);
        foreach (['dv', 'en'] as $locale) {
            if ($title = $request->input("translations.{$locale}.title")) {
                $page->translations()->create(['locale' => $locale, 'title' => $title, 'content' => $request->input("translations.{$locale}.content")]);
            }
        }
        return redirect()->route('admin.pages.index')->with('success', 'Page created.');
    }

    public function edit(Page $page) { $page->load('translations'); return view('admin.pages.edit', compact('page')); }

    public function update(Request $request, Page $page)
    {
        $page->update(['slug' => $request->slug, 'status' => $request->status]);
        foreach (['dv', 'en'] as $locale) {
            if ($title = $request->input("translations.{$locale}.title")) {
                $page->translations()->updateOrCreate(['locale' => $locale], ['title' => $title, 'content' => $request->input("translations.{$locale}.content")]);
            }
        }
        return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
    }

    public function destroy(Page $page) { $page->delete(); return redirect()->route('admin.pages.index'); }
}
