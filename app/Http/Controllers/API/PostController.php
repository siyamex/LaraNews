<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::published()
            ->with(['translations', 'category.translations', 'user', 'tags.translations'])
            ->when($request->category, fn($q) => $q->whereHas('category.translations', fn($q) => $q->where('slug', $request->category)))
            ->when($request->tag, fn($q) => $q->whereHas('tags', fn($q) => $q->where('slug', $request->tag)))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->search, fn($q) => $q->whereHas('translations', fn($q) => $q->where('title', 'like', "%{$request->search}%")))
            ->when($request->featured, fn($q) => $q->where('is_featured', true))
            ->when($request->breaking, fn($q) => $q->where('is_breaking', true))
            ->orderByDesc($request->get('sort', 'published_at'))
            ->paginate($request->get('per_page', 15));

        return new PostCollection($posts);
    }

    public function show(Request $request, string $slug)
    {
        $post = Post::published()
            ->with(['translations', 'category.translations', 'tags.translations', 'user', 'authors'])
            ->whereHas('translations', fn($q) => $q->where('slug', $slug))
            ->firstOrFail();

        $post->incrementViews();

        return new PostResource($post);
    }

    public function trending(Request $request)
    {
        $posts = Post::published()
            ->with(['translations', 'category.translations', 'user'])
            ->orderByDesc('views_count')
            ->take(10)
            ->get();

        return PostResource::collection($posts);
    }

    public function breaking(Request $request)
    {
        $posts = Post::published()
            ->where('is_breaking', true)
            ->with(['translations', 'category.translations', 'user'])
            ->latest('published_at')
            ->take(5)
            ->get();

        return PostResource::collection($posts);
    }

    public function comments(Request $request, string $slug)
    {
        $post = Post::published()
            ->whereHas('translations', fn($q) => $q->where('slug', $slug))
            ->firstOrFail();

        $comments = $post->comments()
            ->approved()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->withCount('replies')
            ->latest()
            ->paginate(20);

        return CommentResource::collection($comments);
    }
}
