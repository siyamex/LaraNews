<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterList;
use Illuminate\Http\Request;

class NewsletterListController extends Controller
{
    public function index() { return view('admin.newsletter.lists', ['lists' => NewsletterList::withCount('subscribers')->get()]); }
    public function create() { return view('admin.newsletter.list-create'); }
    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'slug' => 'required|unique:newsletter_lists']);
        NewsletterList::create($request->except('_token'));
        return redirect()->route('admin.newsletter.lists.index');
    }
    public function edit(NewsletterList $list) { return view('admin.newsletter.list-edit', compact('list')); }
    public function update(Request $request, NewsletterList $list) { $list->update($request->except('_token', '_method')); return redirect()->route('admin.newsletter.lists.index'); }
    public function destroy(NewsletterList $list) { $list->delete(); return redirect()->route('admin.newsletter.lists.index'); }
}
