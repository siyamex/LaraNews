<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function index() { return view('admin.polls.index', ['polls' => Poll::with('post.translations')->withCount('votes')->latest()->paginate(20)]); }
    public function create() { return view('admin.polls.create'); }
    public function store(Request $request) { Poll::create($request->except('_token')); return redirect()->route('admin.polls.index'); }
    public function edit(Poll $poll) { $poll->load('options'); return view('admin.polls.edit', compact('poll')); }
    public function update(Request $request, Poll $poll) { $poll->update($request->except('_token', '_method')); return redirect()->route('admin.polls.index'); }
    public function destroy(Poll $poll) { $poll->delete(); return redirect()->route('admin.polls.index'); }
}
