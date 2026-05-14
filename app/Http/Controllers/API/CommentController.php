<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request, $slug)
    {
        $post = Post::whereHas('translations', fn($q) => $q->where('slug', $slug))->firstOrFail();

        $comments = $post->comments()
            ->approved()
            ->with('user')
            ->whereNull('parent_id')
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $comments->map(fn($c) => $this->commentResource($c)),
            'meta' => ['total' => $comments->total(), 'current_page' => $comments->currentPage(), 'last_page' => $comments->lastPage()],
        ]);
    }

    public function store(Request $request, $slug)
    {
        $post = Post::published()
            ->whereHas('translations', fn($q) => $q->where('slug', $slug))
            ->firstOrFail();

        $data = $request->validate([
            'content'   => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $post->comments()->create([
            'user_id'   => auth()->id(),
            'content'   => $data['content'],
            'parent_id' => $data['parent_id'] ?? null,
            'status'    => 'pending',
        ]);

        return response()->json($this->commentResource($comment), 201);
    }

    public function destroy(Request $request, Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    private function commentResource(Comment $comment): array
    {
        return [
            'id'         => $comment->id,
            'content'    => $comment->content,
            'status'     => $comment->status,
            'created_at' => $comment->created_at->toIso8601String(),
            'user'       => $comment->user ? ['id' => $comment->user->id, 'name' => $comment->user->name] : null,
            'replies_count' => $comment->replies_count ?? 0,
        ];
    }
}
