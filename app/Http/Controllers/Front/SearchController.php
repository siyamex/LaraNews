<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request, string $locale)
    {
        $query = $request->get('q', '');
        $posts = collect();

        if (strlen($query) >= 2) {
            $posts = $this->search($query, $locale);
        }

        $seo = [
            'title'   => __('news.search') . ': ' . $query . ' — ' . config('app.name'),
            'og_type' => 'website',
        ];

        return view('front.search.index', compact('query', 'posts', 'seo', 'locale'));
    }

    public function suggest(Request $request, string $locale)
    {
        $query = $request->get('q', '');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = $this->dbSearch($query, $locale, 6);

        return response()->json($results->map(fn($post) => [
            'title' => $post->translation($locale)?->title,
            'url'   => route('news.show', ['locale' => $locale, 'slug' => $post->translation($locale)?->slug]),
            'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
        ]));
    }

    private function search(string $query, string $locale)
    {
        return $this->dbSearch($query, $locale, 15);
    }

    private function dbSearch(string $query, string $locale, int $limit)
    {
        return Post::published()
            ->whereHas('translations', fn($q) => $q->where('locale', $locale)
                ->where(fn($q) => $q
                    ->where('title', 'like', "%{$query}%")
                    ->orWhere('excerpt', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                ))
            ->with(['translations' => fn($q) => $q->where('locale', $locale), 'category.translations', 'user'])
            ->latest('published_at')
            ->paginate($limit);
    }
}
