<?php

namespace App\Livewire\Front;

use App\Models\Bookmark;
use App\Models\Post;
use Livewire\Component;

class BookmarkButton extends Component
{
    public Post $post;
    public bool $bookmarked = false;

    public function mount(): void
    {
        $this->bookmarked = auth()->check() && auth()->user()->hasBookmarked($this->post);
    }

    public function toggle(): void
    {
        if (! auth()->check()) {
            $this->dispatch('open-login-modal');
            return;
        }

        if ($this->bookmarked) {
            Bookmark::where('user_id', auth()->id())->where('post_id', $this->post->id)->delete();
            $this->post->decrement('bookmarks_count');
        } else {
            Bookmark::create(['user_id' => auth()->id(), 'post_id' => $this->post->id]);
            $this->post->increment('bookmarks_count');
        }

        $this->bookmarked = ! $this->bookmarked;
    }

    public function render()
    {
        return view('livewire.front.bookmark-button');
    }
}
