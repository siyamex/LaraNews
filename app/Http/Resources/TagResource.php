<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->get('locale', app()->getLocale());
        $translation = $this->translations?->firstWhere('locale', $locale)
            ?? $this->translations?->first();

        return [
            'id'          => $this->id,
            'slug'        => $this->slug,
            'name'        => $translation?->name ?? $this->slug,
            'posts_count' => $this->posts_count ?? null,
            'url'         => route('tag.show', ['locale' => $locale, 'slug' => $this->slug]),
        ];
    }
}
