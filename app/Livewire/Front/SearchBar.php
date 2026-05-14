<?php

namespace App\Livewire\Front;

use App\Models\Post;
use Livewire\Component;

class SearchBar extends Component
{
    public string $query = '';
    public array $results = [];
    public bool $open = false;

    public function updatedQuery(): void
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            $this->open    = false;
            return;
        }

        $locale = app()->getLocale();

        $posts = Post::search($this->query)
            ->query(fn($q) => $q->published()
                ->with(['translations' => fn($q) => $q->where('locale', $locale)]))
            ->take(6)
            ->get();

        $this->results = $posts->map(fn($post) => [
            'title' => $post->translation($locale)?->title,
            'url'   => route('news.show', ['locale' => $locale, 'slug' => $post->translation($locale)?->slug]),
            'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
            'category' => $post->category?->getName($locale),
        ])->toArray();

        $this->open = count($this->results) > 0;
    }

    public function clear(): void
    {
        $this->query   = '';
        $this->results = [];
        $this->open    = false;
    }

    public function render()
    {
        return view('livewire.front.search-bar');
    }
}
