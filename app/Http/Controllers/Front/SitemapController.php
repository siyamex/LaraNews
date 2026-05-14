<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemaps = [
            'posts'      => route('sitemap.posts'),
            'categories' => route('sitemap.categories'),
            'tags'       => route('sitemap.tags'),
        ];

        return response()->view('front.sitemap.index', compact('sitemaps'))
            ->header('Content-Type', 'application/xml');
    }

    public function posts()
    {
        $posts = Cache::remember('sitemap_posts', 3600, fn() =>
            Post::published()
                ->with('translations')
                ->latest('published_at')
                ->get(['id', 'updated_at', 'published_at'])
        );

        return response()->view('front.sitemap.posts', compact('posts'))
            ->header('Content-Type', 'application/xml');
    }

    public function categories()
    {
        $categories = Category::active()->with('translations')->get();

        return response()->view('front.sitemap.categories', compact('categories'))
            ->header('Content-Type', 'application/xml');
    }

    public function tags()
    {
        $tags = Tag::with('translations')->get();

        return response()->view('front.sitemap.tags', compact('tags'))
            ->header('Content-Type', 'application/xml');
    }

    public function googleNews()
    {
        $posts = Post::published()
            ->with('translations')
            ->where('published_at', '>=', now()->subDays(2))
            ->latest('published_at')
            ->take(1000)
            ->get();

        return response()->view('front.sitemap.google-news', compact('posts'))
            ->header('Content-Type', 'application/xml');
    }
}
