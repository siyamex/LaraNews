<?php

namespace App\Services;

use App\Enums\PostStatus;
use App\Events\PostPublished;
use App\Jobs\GeneratePostSummary;
use App\Jobs\SendBreakingNewsNotification;
use App\Models\Post;
use App\Models\PostRevision;
use App\Models\PostTranslation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostService
{
    public function __construct(
        private readonly SEOService $seoService,
        private readonly MediaService $mediaService,
    ) {}

    public function create(array $data, int $userId): Post
    {
        return DB::transaction(function () use ($data, $userId) {
            $status = $data['status'] ?? 'draft';
            $publishedAt = $data['published_at'] ?? null;
            if ($status === 'published' && ! $publishedAt) {
                $publishedAt = now();
            }

            $post = Post::create([
                'user_id'                => $userId,
                'category_id'            => $data['category_id'] ?? null,
                'type'                   => $data['type'] ?? 'article',
                'status'                 => $status,
                'featured_image'         => $data['featured_image'] ?? null,
                'featured_image_caption' => $data['featured_image_caption'] ?? null,
                'featured_image_alt'     => $data['featured_image_alt'] ?? null,
                'video_url'              => $data['video_url'] ?? null,
                'audio_url'              => $data['audio_url'] ?? null,
                'is_featured'            => $data['is_featured'] ?? false,
                'is_breaking'            => $data['is_breaking'] ?? false,
                'is_premium'             => $data['is_premium'] ?? false,
                'paywall_type'           => $data['paywall_type'] ?? 'none',
                'free_paragraphs'        => $data['free_paragraphs'] ?? 3,
                'allow_comments'         => $data['allow_comments'] ?? true,
                'allow_reactions'        => $data['allow_reactions'] ?? true,
                'source_url'             => $data['source_url'] ?? null,
                'source_name'            => $data['source_name'] ?? null,
                'content_blocks'         => $data['content_blocks'] ?? null,
                'scheduled_at'           => $data['scheduled_at'] ?? null,
                'published_at'           => $publishedAt,
            ]);

            $this->saveTranslations($post, $data);

            $this->syncTagsFromData($post, $data);
            $this->syncAuthorsFromData($post, $data);

            if ($post->status === PostStatus::Published) {
                $this->onPublished($post);
            }

            return $post->fresh();
        });
    }

    public function update(Post $post, array $data): Post
    {
        return DB::transaction(function () use ($post, $data) {
            $wasPublished = $post->status === PostStatus::Published;
            $newStatus    = $data['status'] ?? $post->status?->value ?? 'draft';

            $this->saveRevisionForAll($post, auth()->id());

            $publishedAt = $post->published_at;
            if ($newStatus === 'published' && ! $wasPublished) {
                $publishedAt = $data['published_at'] ?? now();
            } elseif (isset($data['published_at'])) {
                $publishedAt = $data['published_at'];
            }

            $post->update([
                'category_id'            => $data['category_id'] ?? $post->category_id,
                'status'                 => $newStatus,
                'featured_image'         => $data['featured_image'] ?? $post->featured_image,
                'featured_image_caption' => $data['featured_image_caption'] ?? $post->featured_image_caption,
                'featured_image_alt'     => $data['featured_image_alt'] ?? $post->featured_image_alt,
                'video_url'              => $data['video_url'] ?? $post->video_url,
                'is_featured'            => $data['is_featured'] ?? $post->is_featured,
                'is_breaking'            => $data['is_breaking'] ?? $post->is_breaking,
                'is_premium'             => $data['is_premium'] ?? $post->is_premium,
                'paywall_type'           => $data['paywall_type'] ?? $post->paywall_type,
                'free_paragraphs'        => $data['free_paragraphs'] ?? $post->free_paragraphs,
                'allow_comments'         => $data['allow_comments'] ?? $post->allow_comments,
                'source_url'             => $data['source_url'] ?? $post->source_url,
                'source_name'            => $data['source_name'] ?? $post->source_name,
                'content_blocks'         => $data['content_blocks'] ?? $post->content_blocks,
                'scheduled_at'           => $data['scheduled_at'] ?? $post->scheduled_at,
                'published_at'           => $publishedAt,
            ]);

            $this->saveTranslations($post, $data);

            $this->syncTagsFromData($post, $data);
            $this->syncAuthorsFromData($post, $data);

            if ($newStatus === 'published' && ! $wasPublished) {
                $this->onPublished($post);
            }

            return $post->fresh();
        });
    }

    public function publish(Post $post): void
    {
        $post->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
        $this->onPublished($post);
    }

    private function saveTranslations(Post $post, array $data): void
    {
        // Keyed-by-locale format: ['dv' => [...], 'en' => [...]] (from Livewire PostEditor)
        if (isset($data['translations']) && is_array($data['translations'])) {
            foreach ($data['translations'] as $locale => $localeData) {
                if (! empty($localeData['title'])) {
                    $this->saveTranslation($post, $localeData, $locale);
                }
            }
            return;
        }

        // Flat format: single locale via $data['locale']
        $locale = $data['locale'] ?? 'dv';
        if (! empty($data['title'])) {
            $this->saveTranslation($post, $data, $locale);
        }
    }

    private function saveTranslation(Post $post, array $data, string $locale): PostTranslation
    {
        $title = $data['title'] ?? '';
        $slug = $this->generateUniqueSlug($data['slug'] ?? $title, $locale, $post->id);

        return PostTranslation::updateOrCreate(
            ['post_id' => $post->id, 'locale' => $locale],
            [
                'title'            => $title,
                'slug'             => $slug,
                'excerpt'          => $data['excerpt'] ?? null,
                'content'          => $data['content'] ?? '',
                'meta_title'       => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'og_title'         => $data['og_title'] ?? null,
                'og_description'   => $data['og_description'] ?? null,
                'og_image'         => $data['og_image'] ?? null,
                'canonical_url'    => $data['canonical_url'] ?? null,
                'faq'              => $data['faq'] ?? null,
            ]
        );
    }

    private function syncTagsFromData(Post $post, array $data): void
    {
        // tag_ids: array of existing tag IDs (from Livewire PostEditor)
        if (isset($data['tag_ids'])) {
            $post->tags()->sync($data['tag_ids']);
            return;
        }

        // tags: array of tag names (legacy format)
        if (isset($data['tags'])) {
            $this->syncTags($post, $data['tags'], $data['locale'] ?? 'dv');
        }
    }

    private function syncAuthorsFromData(Post $post, array $data): void
    {
        // author_ids: simple array of user IDs (from Livewire PostEditor)
        if (isset($data['author_ids'])) {
            $post->authors()->sync(
                collect($data['author_ids'])->mapWithKeys(fn($id, $i) => [
                    $id => ['role' => 'author', 'order' => $i]
                ])
            );
            return;
        }

        // authors: array of {id, role, order} objects (legacy format)
        if (! empty($data['authors'])) {
            $post->authors()->sync(
                collect($data['authors'])->mapWithKeys(fn($author) => [
                    $author['id'] => ['role' => $author['role'] ?? 'author', 'order' => $author['order'] ?? 0]
                ])
            );
        }
    }

    private function generateUniqueSlug(string $title, string $locale, ?int $postId = null): string
    {
        $slug = Str::slug($title);
        if (empty($slug)) {
            $slug = 'post-' . time();
        }

        $query = PostTranslation::where('locale', $locale)->where('slug', $slug);
        if ($postId) {
            $query->where('post_id', '!=', $postId);
        }

        $count = 1;
        $originalSlug = $slug;
        while ($query->clone()->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    private function syncTags(Post $post, array $tags, string $locale): void
    {
        $tagIds = collect($tags)->map(function ($tagName) use ($locale) {
            $tag = \App\Models\Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['type' => 'general']
            );
            \App\Models\TagTranslation::firstOrCreate(
                ['tag_id' => $tag->id, 'locale' => $locale],
                ['name' => $tagName]
            );
            return $tag->id;
        });
        $post->tags()->sync($tagIds);
    }

    private function saveRevisionForAll(Post $post, ?int $userId): void
    {
        if ($post->translations->isEmpty()) {
            $post->load('translations');
        }
        foreach ($post->translations as $translation) {
            PostRevision::create([
                'post_id' => $post->id,
                'user_id' => $userId,
                'locale'  => $translation->locale,
                'title'   => $translation->title,
                'content' => $translation->content,
            ]);
        }
    }

    private function onPublished(Post $post): void
    {
        event(new PostPublished($post));

        if ($post->is_breaking) {
            SendBreakingNewsNotification::dispatch($post)->onQueue('notifications');
        }

        GeneratePostSummary::dispatch($post)->onQueue('ai');

        $this->updateCategoryCount($post);
    }

    private function updateCategoryCount(Post $post): void
    {
        if ($post->category_id) {
            $post->category()->increment('posts_count');
        }
    }

    public function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        return max(1, (int) ceil($wordCount / 200));
    }

    public function delete(Post $post): void
    {
        DB::transaction(function () use ($post) {
            if ($post->category_id) {
                $post->category()->decrement('posts_count');
            }
            $post->tags()->each(fn($tag) => $tag->decrement('posts_count'));
            $post->delete();
        });
    }
}
