<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function __construct(private readonly PostService $postService) {}

    public function index(Request $request)
    {
        $posts = Post::with(['translations', 'user', 'category.translations'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->search, fn($q) => $q->whereHas('translations', fn($q) => $q->where('title', 'like', '%' . $request->search . '%')))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->latest()
            ->paginate(20);

        $categories = Category::with('translations')->active()->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::with('translations')->active()->orderBy('order')->get();
        $authors    = User::whereHas('roles', fn($q) => $q->whereIn('name', ['editor', 'journalist', 'author', 'admin', 'super_admin']))->get();
        $tags       = Tag::with('translations')->orderBy('posts_count', 'desc')->take(100)->get();

        return view('admin.posts.create', compact('categories', 'authors', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'               => 'required|in:article,gallery,video,audio,poll,trivia_quiz,personality_quiz,recipe,event,sorted_list,live_blog',
            'status'             => 'required|in:draft,pending,published,scheduled,archived',
            'category_id'        => 'nullable|exists:categories,id',
            'translations'       => 'required|array',
            'translations.*.locale' => 'required|in:dv,en',
            'translations.*.title'  => 'required|string|max:255',
            'translations.*.content'=> 'nullable|string',
        ]);

        $post = $this->postService->create($request->all(), auth()->id());

        Cache::forget('admin_dashboard_stats');

        return redirect()->route('admin.posts.edit', $post)
            ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $post->load(['translations', 'tags.translations', 'category.translations', 'authors', 'poll.options']);

        $categories = Category::with('translations')->active()->orderBy('order')->get();
        $authors    = User::whereHas('roles', fn($q) => $q->whereIn('name', ['editor', 'journalist', 'author', 'admin', 'super_admin']))->get();
        $tags       = Tag::with('translations')->orderBy('posts_count', 'desc')->take(100)->get();

        return view('admin.posts.edit', compact('post', 'categories', 'authors', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $this->postService->update($post, $request->all());

        Cache::forget('admin_dashboard_stats');

        return redirect()->route('admin.posts.edit', $post)
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        Cache::forget('admin_dashboard_stats');

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted.');
    }

    public function publish(Post $post)
    {
        $post->update(['status' => 'published', 'published_at' => $post->published_at ?? now()]);
        Cache::forget('admin_dashboard_stats');
        return response()->json(['message' => 'Published.']);
    }

    public function clone(Post $post)
    {
        $clone = $post->replicate();
        $clone->status = 'draft';
        $clone->uuid   = \Illuminate\Support\Str::uuid();
        $clone->push();

        foreach ($post->translations as $t) {
            $translation = $t->replicate();
            $translation->post_id = $clone->id;
            $translation->slug    = $t->slug . '-copy';
            $translation->save();
        }

        return redirect()->route('admin.posts.edit', $clone)->with('success', 'Post cloned.');
    }

    public function toggleBreaking(Post $post)
    {
        $post->update(['is_breaking' => ! $post->is_breaking]);
        return response()->json(['is_breaking' => $post->is_breaking]);
    }

    public function revisions(Post $post)
    {
        $revisions = $post->revisions()->with('user')->latest()->paginate(20);
        return view('admin.posts.revisions', compact('post', 'revisions'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'action' => 'required|in:publish,draft,archive,delete']);

        $posts = Post::whereIn('id', $request->ids);

        match ($request->action) {
            'publish' => $posts->update(['status' => 'published', 'published_at' => now()]),
            'draft'   => $posts->update(['status' => 'draft']),
            'archive' => $posts->update(['status' => 'archived']),
            'delete'  => $posts->get()->each->delete(),
        };

        return response()->json(['message' => 'Bulk action completed.']);
    }
}
