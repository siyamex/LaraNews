<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FeedController extends Controller
{
    public function rss(string $locale)
    {
        $posts = Cache::remember('rss_feed_' . $locale, 1800, fn() =>
            Post::published()
                ->with(['translations' => fn($q) => $q->where('locale', $locale), 'category.translations', 'user'])
                ->latest('published_at')
                ->take(50)
                ->get()
        );

        return response()->view('front.feed.rss', compact('posts', 'locale'))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    public function atom(string $locale)
    {
        $posts = Cache::remember('atom_feed_' . $locale, 1800, fn() =>
            Post::published()
                ->with(['translations' => fn($q) => $q->where('locale', $locale), 'user'])
                ->latest('published_at')
                ->take(50)
                ->get()
        );

        return response()->view('front.feed.atom', compact('posts', 'locale'))
            ->header('Content-Type', 'application/atom+xml; charset=UTF-8');
    }

    public function category(string $locale, string $slug)
    {
        $category = Category::whereHas('translations', fn($q) => $q->where('locale', $locale)->where('slug', $slug))
            ->firstOrFail();

        $posts = Post::published()
            ->where('category_id', $category->id)
            ->with(['translations' => fn($q) => $q->where('locale', $locale), 'user'])
            ->latest('published_at')
            ->take(25)
            ->get();

        return response()->view('front.feed.rss', compact('posts', 'locale'))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
