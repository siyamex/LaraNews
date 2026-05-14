<?php

namespace App\Livewire\Front;

use App\Models\Post;
use Livewire\Component;

class LoadMorePosts extends Component
{
    public int $loaded;
    public int $perPage = 12;
    public bool $hasMore = true;

    public function loadMore(): void
    {
        $this->loaded += $this->perPage;
    }

    public function render()
    {
        $locale = app()->getLocale();

        $posts = Post::published()
            ->with(['translations' => fn($q) => $q->where('locale', $locale), 'category.translations', 'user'])
            ->latest('published_at')
            ->skip($this->loaded - $this->perPage)
            ->take($this->perPage)
            ->get();

        $total    = Post::published()->count();
        $this->hasMore = $this->loaded < $total;

        return view('livewire.front.load-more-posts', compact('posts'));
    }
}
