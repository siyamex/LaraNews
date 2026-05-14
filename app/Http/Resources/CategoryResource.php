<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->get('locale', app()->getLocale());
        $translation = $this->translations?->firstWhere('locale', $locale)
            ?? $this->translations?->first();

        return [
            'id'          => $this->id,
            'slug'        => $translation?->slug ?? $this->slug,
            'name'        => $translation?->name,
            'description' => $translation?->description,
            'cover_image' => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'color'       => $this->color,
            'posts_count' => $this->posts_count ?? null,
            'parent_id'   => $this->parent_id,
            'url'         => $translation ? route('category.show', ['locale' => $locale, 'slug' => $translation->slug ?? $this->slug]) : null,
        ];
    }
}
