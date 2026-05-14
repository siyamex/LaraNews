<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;

class AIService
{
    public function generateSummary(string $content, string $locale = 'dv', int $sentences = 3): string
    {
        $cleanContent = strip_tags($content);
        $lang = $locale === 'dv' ? 'Dhivehi' : 'English';

        $response = OpenAI::chat()->create([
            'model' => config('openai.model', 'gpt-4o'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are a professional news editor. Generate a concise {$sentences}-sentence summary in {$lang}. Return only the summary, no extra text.",
                ],
                ['role' => 'user', 'content' => "Summarize: {$cleanContent}"],
            ],
            'max_tokens' => 300,
            'temperature' => 0.5,
        ]);

        return trim($response->choices[0]->message->content ?? '');
    }

    public function generateHeadlines(string $content, string $locale = 'dv', int $count = 5): array
    {
        $lang = $locale === 'dv' ? 'Dhivehi' : 'English';

        $response = OpenAI::chat()->create([
            'model' => config('openai.model', 'gpt-4o'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Generate {$count} compelling news headlines in {$lang}. Return as JSON array of strings.",
                ],
                ['role' => 'user', 'content' => strip_tags($content)],
            ],
            'max_tokens' => 400,
            'temperature' => 0.8,
            'response_format' => ['type' => 'json_object'],
        ]);

        $result = json_decode($response->choices[0]->message->content ?? '{}', true);
        return $result['headlines'] ?? [];
    }

    public function generateTags(string $content, string $locale = 'dv', int $count = 10): array
    {
        $lang = $locale === 'dv' ? 'Dhivehi' : 'English';

        $response = OpenAI::chat()->create([
            'model' => config('openai.model', 'gpt-4o'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Extract {$count} relevant tags/keywords in {$lang} from this article. Return as JSON: {\"tags\": [\"tag1\", \"tag2\"]}",
                ],
                ['role' => 'user', 'content' => strip_tags($content)],
            ],
            'max_tokens' => 200,
            'temperature' => 0.3,
            'response_format' => ['type' => 'json_object'],
        ]);

        $result = json_decode($response->choices[0]->message->content ?? '{}', true);
        return $result['tags'] ?? [];
    }

    public function generateSeoMeta(string $title, string $content, string $locale = 'dv'): array
    {
        $lang = $locale === 'dv' ? 'Dhivehi' : 'English';

        $response = OpenAI::chat()->create([
            'model' => config('openai.model', 'gpt-4o'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Generate SEO meta tags in {$lang}. Return JSON: {\"meta_title\": \"...\", \"meta_description\": \"...\", \"og_title\": \"...\", \"og_description\": \"\"}. Keep meta_description under 160 chars.",
                ],
                ['role' => 'user', 'content' => "Title: {$title}\n\nContent: " . substr(strip_tags($content), 0, 1000)],
            ],
            'max_tokens' => 400,
            'temperature' => 0.4,
            'response_format' => ['type' => 'json_object'],
        ]);

        return json_decode($response->choices[0]->message->content ?? '{}', true) ?? [];
    }

    public function generateSocialCaption(string $title, string $excerpt, string $locale = 'dv'): string
    {
        $lang = $locale === 'dv' ? 'Dhivehi' : 'English';

        $response = OpenAI::chat()->create([
            'model' => config('openai.model', 'gpt-4o'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Write a social media caption in {$lang} under 280 characters. Add relevant emojis. Return only the caption.",
                ],
                ['role' => 'user', 'content' => "Title: {$title}\nExcerpt: {$excerpt}"],
            ],
            'max_tokens' => 150,
            'temperature' => 0.7,
        ]);

        return trim($response->choices[0]->message->content ?? '');
    }

    public function enhanceGrammar(string $content, string $locale = 'dv'): string
    {
        $lang = $locale === 'dv' ? 'Dhivehi' : 'English';

        $response = OpenAI::chat()->create([
            'model' => config('openai.model', 'gpt-4o'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Improve grammar, spelling and style of this {$lang} news article. Preserve meaning. Return only the corrected text.",
                ],
                ['role' => 'user', 'content' => $content],
            ],
            'max_tokens' => 4000,
            'temperature' => 0.2,
        ]);

        return trim($response->choices[0]->message->content ?? $content);
    }

    public function translateContent(string $content, string $fromLocale, string $toLocale): string
    {
        $fromLang = $fromLocale === 'dv' ? 'Dhivehi' : 'English';
        $toLang = $toLocale === 'dv' ? 'Dhivehi' : 'English';

        $response = OpenAI::chat()->create([
            'model' => config('openai.model', 'gpt-4o'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are a professional translator specializing in news media. Translate from {$fromLang} to {$toLang}. Preserve HTML formatting. Return only the translated content.",
                ],
                ['role' => 'user', 'content' => $content],
            ],
            'max_tokens' => 4000,
            'temperature' => 0.3,
        ]);

        return trim($response->choices[0]->message->content ?? '');
    }

    public function rewriteRssContent(string $title, string $content, string $locale = 'dv'): array
    {
        $lang = $locale === 'dv' ? 'Dhivehi' : 'English';

        $response = OpenAI::chat()->create([
            'model' => config('openai.model', 'gpt-4o'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Rewrite this news article in {$lang} in a professional style. Return JSON: {\"title\": \"...\", \"content\": \"...\", \"excerpt\": \"...\"}",
                ],
                ['role' => 'user', 'content' => "Title: {$title}\n\nContent: {$content}"],
            ],
            'max_tokens' => 2000,
            'temperature' => 0.6,
            'response_format' => ['type' => 'json_object'],
        ]);

        return json_decode($response->choices[0]->message->content ?? '{}', true) ?? [];
    }

    public function checkFakeNewsRisk(string $title, string $content): array
    {
        $response = OpenAI::chat()->create([
            'model' => config('openai.model', 'gpt-4o'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Analyze this news article for misinformation risk. Return JSON: {"risk_level": "low|medium|high", "flags": [], "suggestions": []}',
                ],
                ['role' => 'user', 'content' => "Title: {$title}\n\nContent: " . substr(strip_tags($content), 0, 2000)],
            ],
            'max_tokens' => 500,
            'temperature' => 0.2,
            'response_format' => ['type' => 'json_object'],
        ]);

        return json_decode($response->choices[0]->message->content ?? '{}', true) ?? ['risk_level' => 'unknown'];
    }

    public function generateArticle(string $topic, string $locale = 'dv', string $style = 'news'): array
    {
        $lang = $locale === 'dv' ? 'Dhivehi' : 'English';

        $response = OpenAI::chat()->create([
            'model' => config('openai.model', 'gpt-4o'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Write a complete {$style} article in {$lang}. Return JSON: {\"title\": \"...\", \"excerpt\": \"...\", \"content\": \"...\" (HTML format), \"tags\": []}",
                ],
                ['role' => 'user', 'content' => "Topic: {$topic}"],
            ],
            'max_tokens' => 3000,
            'temperature' => 0.7,
            'response_format' => ['type' => 'json_object'],
        ]);

        return json_decode($response->choices[0]->message->content ?? '{}', true) ?? [];
    }

    private function callGemini(string $prompt): string
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post('https://generativelanguage.googleapis.com/v1beta/models/' . config('services.gemini.model', 'gemini-1.5-pro') . ':generateContent?key=' . config('services.gemini.api_key'), [
                'contents' => [['parts' => [['text' => $prompt]]]],
            ]);

        return $response->json('candidates.0.content.parts.0.text', '');
    }
}
