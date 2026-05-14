<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePostSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Post $post) {}

    public function handle(): void
    {
        // AI summary generation - skipped if no API key configured
        if (! config('services.anthropic.key')) {
            return;
        }

        try {
            $aiService = app(\App\Services\AIService::class);
            $translation = $this->post->translations->first();
            if (! $translation) {
                return;
            }
            $summary = $aiService->generateSummary($translation->content, $translation->locale);
            $this->post->update(['ai_summary' => $summary]);
        } catch (\Throwable) {
            // Silent fail — AI summary is non-critical
        }
    }
}
