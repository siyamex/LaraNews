<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function vote(Request $request, Poll $poll)
    {
        if ($poll->isExpired()) {
            return response()->json(['error' => 'Poll has ended.'], 422);
        }

        $request->validate(['option_id' => 'required|exists:poll_options,id']);

        $identifier = auth()->id() ?? session()->getId();
        $field      = auth()->check() ? 'user_id' : 'session_id';

        $existing = PollVote::where('poll_id', $poll->id)->where($field, $identifier)->first();
        if ($existing) {
            return response()->json(['error' => 'Already voted.'], 422);
        }

        PollVote::create([
            'poll_id'        => $poll->id,
            'poll_option_id' => $request->option_id,
            'user_id'        => auth()->id(),
            'session_id'     => session()->getId(),
            'ip_address'     => $request->ip(),
        ]);

        $poll->options()->where('id', $request->option_id)->increment('votes_count');
        $poll->increment('total_votes');

        return response()->json(['message' => 'Vote recorded.', 'results' => $poll->fresh(['options'])->options]);
    }
}
