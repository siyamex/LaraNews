<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Services\SEOService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private readonly SEOService $seoService) {}

    public function show(Request $request, string $locale, string $slug)
    {
        $category = Category::whereHas('translations', fn($q) => $q->where('locale', $locale)->where('slug', $slug))
            ->with(['translations', 'children.translations'])
            ->firstOrFail();

        $posts = Post::published()
            ->where('category_id', $category->id)
            ->with(['translations' => fn($q) => $q->where('locale', $locale), 'category.translations', 'user'])
            ->latest('published_at')
            ->paginate(15);

        $seo = [
            'title'       => $category->getName($locale) . ' — ' . config('app.name'),
            'description' => $category->getTranslation('description', $locale) ?? $category->getName($locale),
            'og_type'     => 'website',
        ];

        return view('front.category.show', compact('category', 'posts', 'seo', 'locale'));
    }
}
