<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'username' => $this->username ?? null,
            'avatar'   => $this->profile_photo_url,
            'bio'      => $this->bio ?? null,
            'role'     => $this->roles?->first()?->name,
            'url'      => route('author.show', ['locale' => app()->getLocale(), 'username' => $this->username ?? $this->id]),
        ];
    }
}
