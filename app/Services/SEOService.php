<?php

namespace App\Services;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostTranslation;
use Illuminate\Support\Facades\Cache;

class SEOService
{
    public function getPostMeta(Post $post, string $locale = null): array
    {
        $locale ??= app()->getLocale();
        $translation = $post->translation($locale);
        $siteName = config('app.name');

        return [
            'title' => $translation?->effective_meta_title ?? $translation?->title ?? '',
            'description' => $translation?->effective_meta_description ?? '',
            'og_title' => $translation?->effective_og_title ?? '',
            'og_description' => $translation?->og_description ?? $translation?->effective_meta_description ?? '',
            'og_image' => $translation?->og_image ?? $post->featured_image,
            'og_type' => 'article',
            'canonical' => $translation?->canonical_url ?? route('news.show', ['locale' => $locale, 'slug' => $translation?->slug]),
            'robots' => 'index,follow',
            'article_published_time' => $post->published_at?->toIso8601String(),
            'article_modified_time' => $post->updated_at->toIso8601String(),
            'article_author' => $post->user?->name,
            'article_section' => $post->category?->getName($locale),
            'site_name' => $siteName,
            'locale' => $this->getLocaleForHtml($locale),
            'is_rtl' => $this->isRtl($locale),
        ];
    }

    public function getArticleSchema(Post $post, string $locale = null): array
    {
        $locale ??= app()->getLocale();
        $translation = $post->translation($locale);

        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $translation?->title ?? '',
            'description' => $translation?->excerpt ?? '',
            'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
            'author' => [
                '@type' => 'Person',
                'name' => $post->user?->name,
                'url' => $post->user?->profile_url,
            ],
            'publisher' => [
                '@type' => 'NewsMediaOrganization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('news.show', ['locale' => $locale, 'slug' => $translation?->slug]),
            ],
            'articleSection' => $post->category?->getName($locale),
            'inLanguage' => $this->getLocaleForHtml($locale),
            'isAccessibleForFree' => !$post->is_premium,
        ];
    }

    public function getFaqSchema(array $faqs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)->map(fn($faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ])->toArray(),
        ];
    }

    public function getBreadcrumbSchema(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->map(fn($item, $idx) => [
                '@type' => 'ListItem',
                'position' => $idx + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ])->toArray(),
        ];
    }

    public function getHreflangLinks(Post $post): array
    {
        $links = [];
        foreach ($post->translations as $translation) {
            $links[$translation->locale] = route('news.show', [
                'locale' => $translation->locale,
                'slug' => $translation->slug,
            ]);
        }
        return $links;
    }

    public function getCategoryHreflangLinks(Category $category): array
    {
        $links = [];
        foreach ($category->translations as $translation) {
            $links[$translation->locale] = route('category.show', [
                'locale' => $translation->locale,
                'slug' => $translation->slug ?? $category->slug,
            ]);
        }
        return $links;
    }

    public function getLocaleForHtml(string $locale): string
    {
        return match($locale) {
            'dv' => 'dv',
            'en' => 'en-US',
            'ar' => 'ar',
            default => $locale,
        };
    }

    public function isRtl(string $locale): bool
    {
        return in_array($locale, ['dv', 'ar', 'fa', 'he', 'ur']);
    }

    public function getMetaRobots(Post $post): string
    {
        if ($post->status !== PostStatus::Published) {
            return 'noindex,nofollow';
        }
        return $post->meta['robots'] ?? 'index,follow';
    }
}
