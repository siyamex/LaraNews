<?php

namespace App\Listeners;

use App\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Event;

class NotifyAuthorOnComment
{
    public function handle(object $event): void
    {
        $comment = $event->comment ?? null;
        if (! $comment) return;

        $post   = $comment->commentable;
        $author = $post?->user;

        if (! $author || $author->id === $comment->user_id) return;

        $author->notify(new NewCommentNotification($comment));
    }
}
