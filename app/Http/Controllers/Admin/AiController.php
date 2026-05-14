<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\AIService;
use Illuminate\Http\Request;

class AiController extends Controller
{
    public function __construct(private readonly AIService $aiService) {}

    public function index()
    {
        return view('admin.ai.index');
    }

    public function generateArticle(Request $request)
    {
        $request->validate(['topic' => 'required|string|max:255', 'locale' => 'required|in:dv,en']);

        try {
            $content = $this->aiService->generateArticle($request->topic, $request->locale);
            return response()->json(['content' => $content]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'AI generation failed: ' . $e->getMessage()], 500);
        }
    }

    public function translate(Request $request, Post $post)
    {
        $request->validate(['target_locale' => 'required|in:dv,en']);

        $sourceLocale = $request->target_locale === 'dv' ? 'en' : 'dv';
        $source = $post->translation($sourceLocale);

        if (! $source) {
            return response()->json(['error' => 'No source translation found.'], 422);
        }

        try {
            $translated = $this->aiService->translateContent($source->content, $request->target_locale);
            return response()->json(['content' => $translated]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Translation failed.'], 500);
        }
    }
}
