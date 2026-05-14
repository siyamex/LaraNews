<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(Request $request, string $locale, string $slug)
    {
        $tag = Tag::whereHas('translations', fn($q) => $q->where('locale', $locale)->where('slug', $slug))
            ->with('translations')
            ->firstOrFail();

        $posts = $tag->posts()
            ->published()
            ->with(['translations' => fn($q) => $q->where('locale', $locale), 'category.translations', 'user'])
            ->latest('published_at')
            ->paginate(15);

        $seo = [
            'title'   => '#' . $tag->getName($locale) . ' — ' . config('app.name'),
            'og_type' => 'website',
        ];

        return view('front.tag.show', compact('tag', 'posts', 'seo', 'locale'));
    }
}
