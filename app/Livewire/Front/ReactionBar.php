<?php

namespace App\Livewire\Front;

use App\Models\Post;
use App\Models\Reaction;
use Livewire\Component;

class ReactionBar extends Component
{
    public Post $post;
    public array $counts = [];
    public ?string $userReaction = null;

    public array $types = ['like' => '👍', 'love' => '❤️', 'haha' => '😄', 'wow' => '😮', 'sad' => '😢', 'angry' => '😡'];

    public function mount(): void
    {
        $this->loadCounts();
        if (auth()->check()) {
            $reaction = Reaction::where('reactable_type', Post::class)
                ->where('reactable_id', $this->post->id)
                ->where('user_id', auth()->id())
                ->first();
            $this->userReaction = $reaction?->type;
        }
    }

    public function react(string $type): void
    {
        if (! auth()->check()) {
            $this->dispatch('open-login-modal');
            return;
        }

        $existing = Reaction::where('reactable_type', Post::class)
            ->where('reactable_id', $this->post->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            if ($existing->type === $type) {
                $existing->delete();
                $this->userReaction = null;
            } else {
                $existing->update(['type' => $type]);
                $this->userReaction = $type;
            }
        } else {
            Reaction::create([
                'reactable_type' => Post::class,
                'reactable_id'   => $this->post->id,
                'user_id'        => auth()->id(),
                'type'           => $type,
            ]);
            $this->userReaction = $type;
        }

        $this->loadCounts();
    }

    private function loadCounts(): void
    {
        $raw = Reaction::where('reactable_type', Post::class)
            ->where('reactable_id', $this->post->id)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $this->counts = [];
        foreach (array_keys($this->types) as $type) {
            $this->counts[$type] = $raw[$type] ?? 0;
        }
    }

    public function render()
    {
        return view('livewire.front.reaction-bar');
    }
}
