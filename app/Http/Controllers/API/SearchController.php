<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate(['q' => 'required|string|min:2|max:200']);

        $locale = $request->get('locale', 'en');
        $query  = $request->q;

        $posts = Post::published()
            ->with(['translations', 'category.translations', 'user'])
            ->whereHas('translations', fn($q) => $q->where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%"))
            ->latest('published_at')
            ->paginate(15);

        return response()->json([
            'query' => $query,
            'data'  => $posts->map(fn($p) => [
                'id'          => $p->id,
                'title'       => $p->translations->firstWhere('locale', $locale)?->title,
                'slug'        => $p->translations->firstWhere('locale', $locale)?->slug,
                'excerpt'     => $p->translations->firstWhere('locale', $locale)?->excerpt,
                'cover_image' => $p->cover_image,
                'published_at' => $p->published_at?->toIso8601String(),
                'author'      => $p->user ? ['name' => $p->user->name] : null,
            ]),
            'meta'  => ['total' => $posts->total(), 'last_page' => $posts->lastPage()],
        ]);
    }
}
