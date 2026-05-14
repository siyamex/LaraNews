<?php

namespace App\Livewire\Front;

use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;
    public bool $open = false;
    public array $notifications = [];

    public function mount(): void
    {
        $this->loadCount();
    }

    public function loadCount(): void
    {
        if (auth()->check()) {
            $this->unreadCount = auth()->user()->unreadNotifications()->count();
        }
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
        if ($this->open) {
            $this->loadNotifications();
        }
    }

    public function loadNotifications(): void
    {
        if (! auth()->check()) return;

        $this->notifications = auth()->user()
            ->notifications()
            ->latest()
            ->take(15)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'type'       => $n->data['type'] ?? 'general',
                'data'       => $n->data,
                'read'       => ! is_null($n->read_at),
                'created_at' => $n->created_at->diffForHumans(),
            ])
            ->toArray();
    }

    public function markAllRead(): void
    {
        if (auth()->check()) {
            auth()->user()->unreadNotifications->markAsRead();
            $this->unreadCount = 0;
            $this->loadNotifications();
        }
    }

    public function markRead(string $id): void
    {
        if (auth()->check()) {
            auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
            $this->loadCount();
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.front.notification-bell');
    }
}
