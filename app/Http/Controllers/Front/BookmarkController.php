<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Post;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $existing = Bookmark::where('user_id', auth()->id())->where('post_id', $post->id)->first();

        if ($existing) {
            $existing->delete();
            $post->decrement('bookmarks_count');
            return response()->json(['bookmarked' => false]);
        }

        Bookmark::create(['user_id' => auth()->id(), 'post_id' => $post->id]);
        $post->increment('bookmarks_count');
        return response()->json(['bookmarked' => true]);
    }

    public function index(Request $request, string $locale)
    {
        $bookmarks = auth()->user()->bookmarks()
            ->with(['post.translations', 'post.category.translations'])
            ->latest()
            ->paginate(15);

        return view('front.user.bookmarks', compact('bookmarks', 'locale'));
    }
}
