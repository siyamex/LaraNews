<?php

namespace App\Listeners;

use App\Events\PostPublished;
use App\Notifications\PostPublishedNotification;

class NotifyFollowersOnPublish
{
    public function handle(PostPublished $event): void
    {
        $post   = $event->post;
        $author = $post->user;

        if (! $author) return;

        $author->followers()
            ->chunk(100, function ($followers) use ($post) {
                foreach ($followers as $follower) {
                    $follower->notify(new PostPublishedNotification($post));
                }
            });
    }
}
