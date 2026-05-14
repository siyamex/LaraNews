<?php

namespace App\Http\Controllers\Front;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Services\SEOService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(private readonly SEOService $seoService) {}

    public function index(Request $request, string $locale)
    {
        $posts = Post::published()
            ->forLocale($locale)
            ->with([
                'translations' => fn($q) => $q->where('locale', $locale),
                'category.translations',
                'user',
            ])
            ->latest('published_at')
            ->paginate(15);

        $seo = [
            'title' => ($locale === 'dv' ? 'ހަބަރު' : 'News') . ' — ' . config('app.name'),
            'description' => $locale === 'dv' ? 'ހުރިހާ ހަބަރު' : 'All news from ' . config('app.name'),
            'og_type' => 'website',
        ];

        return view('front.news.index', compact('posts', 'seo'));
    }

    public function show(Request $request, string $locale, string $slug)
    {
        $translation = PostTranslation::where('locale', $locale)
            ->where('slug', $slug)
            ->with(['post.user', 'post.category.translations', 'post.tags.translations'])
            ->firstOrFail();

        $post = $translation->post;

        if ($post->status !== PostStatus::Published) {
            abort(404);
        }

        // Eager-load all locale translations
        $post->load([
            'translations',
            'user',
            'category.translations',
            'tags.translations',
            'authors',
            'poll.options',
        ]);

        // Track page view (async via event)
        $post->incrementViews();

        // Related posts
        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->with(['translations' => fn($q) => $q->where('locale', $locale), 'user'])
            ->inRandomOrder()
            ->take(5)
            ->get();

        $seo = $this->seoService->getPostMeta($post, $locale);
        $hreflang = $this->seoService->getHreflangLinks($post);
        $schemas = [
            $this->seoService->getArticleSchema($post, $locale),
            $this->seoService->getBreadcrumbSchema([
                ['name' => __('news.home'), 'url' => route('home', ['locale' => $locale])],
                $post->category ? ['name' => $post->category->getName($locale), 'url' => route('category.show', ['locale' => $locale, 'slug' => $post->category->getSlugForLocale($locale)])] : null,
                ['name' => $translation->title, 'url' => route('news.show', ['locale' => $locale, 'slug' => $slug])],
            ]),
        ];

        if ($translation->faq) {
            $schemas[] = $this->seoService->getFaqSchema($translation->faq);
        }

        return view('front.news.show', compact('post', 'translation', 'related', 'seo', 'hreflang', 'schemas'));
    }

    public function breaking(Request $request, string $locale)
    {
        $posts = Post::breaking()
            ->with(['translations' => fn($q) => $q->where('locale', $locale)])
            ->latest('published_at')
            ->take(10)
            ->get();

        return response()->json($posts->map(fn($p) => [
            'id' => $p->id,
            'title' => $p->translation($locale)?->title,
            'url' => route('news.show', ['locale' => $locale, 'slug' => $p->translation($locale)?->slug]),
        ]));
    }
}
