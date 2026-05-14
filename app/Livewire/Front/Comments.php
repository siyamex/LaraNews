<?php

namespace App\Livewire\Front;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use WithPagination;

    public Post $post;
    public string $content = '';
    public string $guestName = '';
    public string $guestEmail = '';
    public ?int $parentId = null;
    public ?string $replyingTo = null;

    public function submit(): void
    {
        $this->validate([
            'content'    => 'required|string|min:2|max:2000',
            'guestName'  => auth()->check() ? 'nullable' : 'required|string|max:100',
            'guestEmail' => auth()->check() ? 'nullable' : 'required|email|max:255',
        ]);

        $this->post->comments()->create([
            'user_id'    => auth()->id(),
            'parent_id'  => $this->parentId,
            'guest_name' => auth()->check() ? null : $this->guestName,
            'guest_email'=> auth()->check() ? null : $this->guestEmail,
            'content'    => $this->content,
            'status'     => auth()->check() ? 'approved' : 'pending',
            'ip_address' => request()->ip(),
        ]);

        $this->reset('content', 'parentId', 'replyingTo');
        $this->dispatch('comment-posted');
    }

    public function replyTo(int $commentId, string $authorName): void
    {
        $this->parentId   = $commentId;
        $this->replyingTo = $authorName;
    }

    public function cancelReply(): void
    {
        $this->parentId   = null;
        $this->replyingTo = null;
    }

    public function render()
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->where('commentable_type', Post::class)
            ->where('commentable_id', $this->post->id)
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('livewire.front.comments', compact('comments'));
    }
}
