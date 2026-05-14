<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __construct(private readonly ThemeService $themeService) {}

    public function index(Request $request, string $locale)
    {
        $blocks = $this->themeService->getHomepageBlocks();

        $heroSlider = Cache::remember('home_hero_'.$locale, 600, fn() =>
            Post::published()
                ->where('is_featured', true)
                ->with(['translations' => fn($q) => $q->where('locale', $locale), 'category.translations', 'user'])
                ->latest('published_at')
                ->take(6)
                ->get()
        );

        $featured = Cache::remember('home_featured_'.$locale, 600, fn() =>
            Post::published()
                ->where('is_featured', true)
                ->with(['translations' => fn($q) => $q->where('locale', $locale), 'category.translations', 'user'])
                ->latest('published_at')
                ->skip(6)
                ->take(4)
                ->get()
        );

        $latestNews = Post::published()
            ->with(['translations' => fn($q) => $q->where('locale', $locale), 'category.translations', 'user'])
            ->latest('published_at')
            ->take(12)
            ->get();

        $trending = Cache::remember('home_trending_'.$locale, 3600, fn() =>
            Post::trending()
                ->with(['translations' => fn($q) => $q->where('locale', $locale)])
                ->take(8)
                ->get()
        );

        $videos = Cache::remember('home_videos_'.$locale, 600, fn() =>
            Post::published()
                ->where('type', 'video')
                ->with(['translations' => fn($q) => $q->where('locale', $locale), 'user'])
                ->latest('published_at')
                ->take(4)
                ->get()
        );

        $popularTags = Cache::remember('home_tags_'.$locale, 3600, fn() =>
            Tag::with(['translations' => fn($q) => $q->where('locale', $locale)])
                ->orderByDesc('posts_count')
                ->take(20)
                ->get()
        );

        // Category sections (featured categories)
        $categorySections = Cache::remember('home_category_sections_'.$locale, 3600, function () use ($locale) {
            $sections = [];
            $featuredCats = Category::active()->featured()
                ->with(['translations' => fn($q) => $q->where('locale', $locale)])
                ->take(3)
                ->get();

            foreach ($featuredCats as $cat) {
                $posts = Post::published()
                    ->where('category_id', $cat->id)
                    ->with(['translations' => fn($q) => $q->where('locale', $locale), 'user'])
                    ->latest('published_at')
                    ->take(4)
                    ->get();

                if ($posts->count() >= 2) {
                    $sections[] = ['category' => $cat, 'posts' => $posts];
                }
            }
            return $sections;
        });

        $seo = [
            'title' => config('app.name') . ' — ' . ($locale === 'dv' ? 'ދިވެހި ހަބަރު' : 'Maldivian News'),
            'description' => $locale === 'dv' ? 'ދިވެހި ހަބަރުގެ ހަވާލާ. ވަގުތުން ހަބަރު ލިބިދޭ.' : 'Maldives news in Dhivehi and English. Breaking news, latest updates.',
            'og_type' => 'website',
        ];

        return view('front.home', compact(
            'heroSlider', 'featured', 'latestNews', 'trending', 'videos',
            'popularTags', 'categorySections', 'seo'
        ));
    }
}
