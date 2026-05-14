<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage-posts');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage-posts');
    }

    public function update(User $user, Post $post): bool
    {
        if ($user->hasPermissionTo('publish-posts')) return true;
        return $user->hasPermissionTo('manage-posts') && $post->user_id === $user->id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->hasPermissionTo('publish-posts');
    }

    public function publish(User $user): bool
    {
        return $user->hasPermissionTo('publish-posts');
    }
}
