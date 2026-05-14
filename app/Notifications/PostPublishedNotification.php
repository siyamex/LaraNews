<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PostPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Post $post) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $locale = app()->getLocale();
        $translation = $this->post->translation($locale);

        return [
            'type'      => 'post_published',
            'post_id'   => $this->post->id,
            'post_title'=> $translation?->title,
            'post_url'  => $translation ? route('news.show', ['locale' => $locale, 'slug' => $translation->slug]) : null,
            'image'     => $this->post->featured_image ? asset('storage/' . $this->post->featured_image) : null,
        ];
    }
}
