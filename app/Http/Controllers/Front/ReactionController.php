<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $request->validate(['type' => 'required|in:like,love,haha,wow,sad,angry']);

        $existing = Reaction::where('reactable_type', Post::class)
            ->where('reactable_id', $post->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            if ($existing->type === $request->type) {
                $existing->delete();
                return response()->json(['action' => 'removed']);
            }
            $existing->update(['type' => $request->type]);
            return response()->json(['action' => 'changed', 'type' => $request->type]);
        }

        Reaction::create(['reactable_type' => Post::class, 'reactable_id' => $post->id, 'user_id' => auth()->id(), 'type' => $request->type]);
        return response()->json(['action' => 'added', 'type' => $request->type]);
    }
}
