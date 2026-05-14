<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\AIService;
use App\Services\PostService;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostEditor extends Component
{
    use WithFileUploads;

    public ?Post $post = null;

    // Post fields
    public string $type   = 'article';
    public string $status = 'draft';
    public ?int $categoryId   = null;
    public bool $isFeatured   = false;
    public bool $isBreaking   = false;
    public bool $isPremium    = false;
    public string $paywallType = 'hard';
    public int $freeParagraphs = 3;
    public bool $allowComments = true;
    public ?string $sourceName = null;
    public ?string $sourceUrl  = null;
    public ?string $publishedAt = null;
    public ?string $featuredImage = null;
    public ?string $featuredImageCaption = null;
    public array $tagIds = [];
    public array $authorIds = [];

    // Translations
    public array $translations = [
        'dv' => ['title' => '', 'slug' => '', 'excerpt' => '', 'content' => '', 'meta_title' => '', 'meta_description' => ''],
        'en' => ['title' => '', 'slug' => '', 'excerpt' => '', 'content' => '', 'meta_title' => '', 'meta_description' => ''],
    ];

    public string $activeLocale = 'dv';
    public bool $aiLoading = false;
    public string $aiMessage = '';

    // Upload
    public $uploadedImage;

    public function mount(?Post $post = null): void
    {
        if ($post && $post->exists) {
            $this->post       = $post;
            $this->type       = $post->type?->value ?? 'article';
            $this->status     = $post->status?->value ?? 'draft';
            $this->categoryId = $post->category_id;
            $this->isFeatured = $post->is_featured;
            $this->isBreaking = $post->is_breaking;
            $this->isPremium  = $post->is_premium;
            $this->paywallType     = $post->paywall_type?->value ?? 'hard';
            $this->freeParagraphs  = $post->free_paragraphs;
            $this->allowComments   = $post->allow_comments;
            $this->sourceName      = $post->source_name;
            $this->sourceUrl       = $post->source_url;
            $this->publishedAt     = $post->published_at?->format('Y-m-d\TH:i');
            $this->featuredImage   = $post->featured_image;
            $this->featuredImageCaption = $post->featured_image_caption;
            $this->tagIds    = $post->tags->pluck('id')->toArray();
            $this->authorIds = $post->authors->pluck('id')->toArray();

            foreach ($post->translations as $t) {
                $this->translations[$t->locale] = [
                    'title'            => $t->title,
                    'slug'             => $t->slug,
                    'excerpt'          => $t->excerpt ?? '',
                    'content'          => $t->content ?? '',
                    'meta_title'       => $t->meta_title ?? '',
                    'meta_description' => $t->meta_description ?? '',
                ];
            }
        }
    }

    public function updatedTranslationsDvTitle(string $value): void
    {
        if (! $this->post && empty($this->translations['dv']['slug'])) {
            $this->translations['dv']['slug'] = Str::slug($value);
        }
    }

    public function updatedTranslationsEnTitle(string $value): void
    {
        if (! $this->post && empty($this->translations['en']['slug'])) {
            $this->translations['en']['slug'] = Str::slug($value);
        }
    }

    public function save(string $status = 'draft'): void
    {
        $this->status = $status;

        $this->validate([
            'type'   => 'required',
            'status' => 'required',
            'translations.dv.title' => 'required|string|max:255',
        ]);

        $data = [
            'type'          => $this->type,
            'status'        => $this->status,
            'category_id'   => $this->categoryId,
            'is_featured'   => $this->isFeatured,
            'is_breaking'   => $this->isBreaking,
            'is_premium'    => $this->isPremium,
            'paywall_type'  => $this->paywallType,
            'free_paragraphs' => $this->freeParagraphs,
            'allow_comments'  => $this->allowComments,
            'source_name'   => $this->sourceName,
            'source_url'    => $this->sourceUrl,
            'published_at'  => $this->publishedAt ? \Carbon\Carbon::parse($this->publishedAt) : null,
            'featured_image' => $this->featuredImage,
            'featured_image_caption' => $this->featuredImageCaption,
            'translations'  => $this->translations,
            'tag_ids'       => $this->tagIds,
            'author_ids'    => $this->authorIds,
        ];

        $postService = app(PostService::class);

        if ($this->post) {
            $postService->update($this->post, $data);
            $this->dispatch('saved', message: 'Post updated successfully.');
        } else {
            $this->post = $postService->create($data, auth()->id());
            $this->dispatch('saved', message: 'Post created successfully.');
            $this->redirect(route('admin.posts.edit', $this->post));
        }
    }

    public function aiGenerateSummary(): void
    {
        $this->aiLoading = true;
        $content = $this->translations[$this->activeLocale]['content'];

        try {
            $aiService = app(AIService::class);
            $summary   = $aiService->generateSummary($content, $this->activeLocale);

            $this->translations[$this->activeLocale]['excerpt'] = $summary;
            $this->aiMessage = 'Summary generated!';
        } catch (\Exception) {
            $this->aiMessage = 'AI generation failed. Check your API key.';
        }

        $this->aiLoading = false;
    }

    public function aiGenerateSeoMeta(): void
    {
        $this->aiLoading = true;
        $title   = $this->translations[$this->activeLocale]['title'];
        $content = $this->translations[$this->activeLocale]['content'];

        try {
            $aiService = app(AIService::class);
            $meta = $aiService->generateSeoMeta($title, $content, $this->activeLocale);

            $this->translations[$this->activeLocale]['meta_title']       = $meta['title'] ?? '';
            $this->translations[$this->activeLocale]['meta_description']  = $meta['description'] ?? '';
            $this->aiMessage = 'SEO meta generated!';
        } catch (\Exception) {
            $this->aiMessage = 'AI generation failed.';
        }

        $this->aiLoading = false;
    }

    public function uploadFeaturedImage(): void
    {
        $this->validate(['uploadedImage' => 'required|image|max:10240']);

        $mediaService = app(\App\Services\MediaService::class);
        $result = $mediaService->uploadImage($this->uploadedImage, 'posts');

        $this->featuredImage = $result['original'];
        $this->uploadedImage = null;
    }

    public function render()
    {
        $categories = Category::with('translations')->active()->orderBy('order')->get();
        $tags       = Tag::with('translations')->orderByDesc('posts_count')->take(50)->get();
        $authors    = \App\Models\User::whereHas('roles', fn($q) => $q->whereIn('name', ['editor', 'journalist', 'author', 'admin', 'super_admin']))->get();

        return view('livewire.admin.post-editor', compact('categories', 'tags', 'authors'));
    }
}
