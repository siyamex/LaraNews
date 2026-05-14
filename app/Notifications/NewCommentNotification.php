<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Comment $comment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $post = $this->comment->commentable;
        $locale = app()->getLocale();
        $translation = $post?->translation($locale);

        return [
            'type'      => 'new_comment',
            'comment_id'=> $this->comment->id,
            'user_name' => $this->comment->user?->name,
            'post_title'=> $translation?->title,
            'post_url'  => $translation ? route('news.show', ['locale' => $locale, 'slug' => $translation->slug]) : null,
            'excerpt'   => str()->limit($this->comment->body, 80),
        ];
    }
}
