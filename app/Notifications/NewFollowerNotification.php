<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewFollowerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly User $follower) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'            => 'new_follower',
            'follower_id'     => $this->follower->id,
            'follower_name'   => $this->follower->name,
            'follower_avatar' => $this->follower->profile_photo_url,
            'follower_url'    => route('author.show', ['locale' => app()->getLocale(), 'username' => $this->follower->username ?? $this->follower->id]),
        ];
    }
}
