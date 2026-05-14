<?php

namespace App\Services;

use App\Models\Post;
use App\Models\RssItem;
use App\Models\RssSource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RssImportService
{
    public function __construct(
        private readonly PostService $postService,
        private readonly AIService $aiService,
    ) {}

    public function importSource(RssSource $source): int
    {
        try {
            $items = $this->fetchFeed($source->url);
            $imported = 0;

            foreach ($items as $item) {
                if ($this->isDuplicate($source->id, $item['guid'])) {
                    continue;
                }

                $rssItem = RssItem::create([
                    'source_id' => $source->id,
                    'guid' => $item['guid'],
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'link' => $item['link'],
                    'image' => $item['image'] ?? null,
                    'published_at' => $item['published_at'],
                    'status' => $source->auto_publish ? 'imported' : 'pending',
                ]);

                if ($source->auto_publish) {
                    $this->createPost($rssItem, $source);
                    $imported++;
                }
            }

            $source->update([
                'last_fetched_at' => now(),
                'items_imported' => $source->items_imported + $imported,
            ]);

            return $imported;
        } catch (\Exception $e) {
            Log::error("RSS import failed for {$source->name}: " . $e->getMessage());
            return 0;
        }
    }

    private function fetchFeed(string $url): array
    {
        $response = Http::timeout(30)->get($url);
        if (!$response->successful()) {
            throw new \RuntimeException("Failed to fetch RSS feed: {$url}");
        }

        $xml = simplexml_load_string($response->body());
        $items = [];

        foreach ($xml->channel->item as $item) {
            $guid = (string) ($item->guid ?? $item->link);
            $image = $this->extractImage($item);

            $items[] = [
                'guid' => $guid,
                'title' => (string) $item->title,
                'description' => (string) $item->description,
                'link' => (string) $item->link,
                'image' => $image,
                'published_at' => isset($item->pubDate)
                    ? now()->parse((string) $item->pubDate)
                    : now(),
            ];
        }

        return $items;
    }

    private function extractImage(\SimpleXMLElement $item): ?string
    {
        if (isset($item->enclosure) && str_starts_with((string) $item->enclosure['type'], 'image/')) {
            return (string) $item->enclosure['url'];
        }

        $namespaces = $item->getNamespaces(true);
        if (isset($namespaces['media'])) {
            $media = $item->children($namespaces['media']);
            if (isset($media->content)) {
                return (string) $media->content['url'];
            }
        }

        preg_match('/<img[^>]+src="([^">]+)"/i', (string) $item->description, $matches);
        return $matches[1] ?? null;
    }

    private function isDuplicate(int $sourceId, string $guid): bool
    {
        return RssItem::where('source_id', $sourceId)->where('guid', $guid)->exists();
    }

    private function createPost(RssItem $rssItem, RssSource $source): void
    {
        $title = $rssItem->title;
        $content = $rssItem->description;

        if ($source->ai_rewrite) {
            try {
                $rewritten = $this->aiService->rewriteRssContent($title, $content, $source->default_locale);
                $title = $rewritten['title'] ?? $title;
                $content = $rewritten['content'] ?? $content;
            } catch (\Exception $e) {
                Log::warning("AI rewrite failed: " . $e->getMessage());
            }
        }

        $post = $this->postService->create([
            'title' => $title,
            'content' => $content,
            'excerpt' => substr(strip_tags($content), 0, 300),
            'category_id' => $source->category_id,
            'status' => 'published',
            'locale' => $source->default_locale,
            'source_url' => $rssItem->link,
            'source_name' => $source->name,
        ], 1); // System user

        $rssItem->update(['post_id' => $post->id, 'status' => 'imported']);
    }
}
