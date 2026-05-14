<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->get('locale', 'en');

        $bookmarks = $request->user()
            ->bookmarks()
            ->with(['translations', 'category.translations'])
            ->latest('bookmarks.created_at')
            ->paginate(20);

        return response()->json([
            'data' => $bookmarks->map(fn($p) => [
                'id'          => $p->id,
                'title'       => $p->translations->firstWhere('locale', $locale)?->title,
                'slug'        => $p->translations->firstWhere('locale', $locale)?->slug,
                'cover_image' => $p->cover_image,
                'bookmarked_at' => $p->pivot?->created_at,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['post_id' => 'required|exists:posts,id']);

        $request->user()->bookmarks()->syncWithoutDetaching([$data['post_id']]);

        return response()->json(['message' => 'Bookmarked.']);
    }

    public function destroy(Request $request, Post $post)
    {
        $request->user()->bookmarks()->detach($post->id);
        return response()->json(['message' => 'Removed from bookmarks.']);
    }
}
