<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function moderate(User $user): bool { return $user->hasPermissionTo('moderate-comments'); }
    public function delete(User $user, Comment $comment): bool
    {
        return $user->hasPermissionTo('moderate-comments') || $comment->user_id === $user->id;
    }
}
