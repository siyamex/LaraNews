<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(Request $request, User $user)
    {
        if (auth()->id() === $user->id) {
            return response()->json(['error' => 'Cannot follow yourself.'], 422);
        }

        $existing = Follow::where('follower_id', auth()->id())->where('following_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
            auth()->user()->decrement('following_count');
            $user->decrement('followers_count');
            return response()->json(['following' => false]);
        }

        Follow::create(['follower_id' => auth()->id(), 'following_id' => $user->id]);
        auth()->user()->increment('following_count');
        $user->increment('followers_count');
        return response()->json(['following' => true]);
    }
}
