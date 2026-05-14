<?php

namespace App\Livewire\Front;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class AuthorDashboard extends Component
{
    use WithPagination;

    public string $activeTab = 'posts';
    public string $statusFilter = '';
    public string $search = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatusFilter(): void { $this->resetPage(); }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    private function stats(): array
    {
        $user = auth()->user();
        return Cache::remember("author_stats_{$user->id}", 300, fn() => [
            'posts'       => Post::where('user_id', $user->id)->count(),
            'published'   => Post::where('user_id', $user->id)->where('status', 'published')->count(),
            'draft'       => Post::where('user_id', $user->id)->where('status', 'draft')->count(),
            'total_views' => Post::where('user_id', $user->id)->sum('views_count'),
            'this_month'  => Post::where('user_id', $user->id)
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
            'followers'   => $user->followers()->count(),
        ]);
    }

    public function render()
    {
        $user  = auth()->user();
        $locale = app()->getLocale();

        $posts = Post::where('user_id', $user->id)
            ->with(['translations' => fn($q) => $q->where('locale', $locale), 'category.translations'])
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, fn($q) => $q->whereHas('translations', fn($q) => $q->where('title', 'like', "%{$this->search}%")))
            ->latest()
            ->paginate(10);

        $topPosts = Post::where('user_id', $user->id)
            ->published()
            ->with(['translations' => fn($q) => $q->where('locale', $locale)])
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        return view('livewire.front.author-dashboard', [
            'stats'    => $this->stats(),
            'posts'    => $posts,
            'topPosts' => $topPosts,
            'locale'   => $locale,
        ]);
    }
}
