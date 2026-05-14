<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->get('locale', app()->getLocale());
        $translation = $this->translation($locale);

        return [
            'id'          => $this->id,
            'uuid'        => $this->uuid,
            'type'        => $this->type,
            'status'      => $this->status,
            'slug'        => $translation?->slug,
            'title'       => $translation?->title,
            'excerpt'     => $translation?->excerpt,
            'content'     => $this->when($request->routeIs('api.posts.show'), $translation?->content),
            'meta'        => $this->when($request->routeIs('api.posts.show'), [
                'title'       => $translation?->meta_title,
                'description' => $translation?->meta_description,
                'og_image'    => $translation?->og_image,
                'canonical'   => $translation?->canonical_url,
            ]),
            'featured_image'         => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'featured_image_caption' => $this->featured_image_caption,
            'category'    => new CategoryResource($this->whenLoaded('category')),
            'author'      => new UserResource($this->whenLoaded('user')),
            'authors'     => UserResource::collection($this->whenLoaded('authors')),
            'tags'        => TagResource::collection($this->whenLoaded('tags')),
            'is_featured' => $this->is_featured,
            'is_breaking' => $this->is_breaking,
            'is_premium'  => $this->is_premium,
            'paywall_type'=> $this->is_premium ? $this->paywall_type : null,
            'stats'       => [
                'views'     => $this->views_count,
                'comments'  => $this->comments_count,
                'reactions' => $this->reactions_count,
            ],
            'reading_time'  => $this->reading_time,
            'published_at'  => $this->published_at?->toIso8601String(),
            'url'           => $translation ? route('news.show', ['locale' => $locale, 'slug' => $translation->slug]) : null,
        ];
    }
}
