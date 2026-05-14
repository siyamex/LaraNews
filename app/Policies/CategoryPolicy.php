<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermissionTo('manage-categories'); }
    public function create(User $user): bool { return $user->hasPermissionTo('manage-categories'); }
    public function update(User $user): bool { return $user->hasPermissionTo('manage-categories'); }
    public function delete(User $user): bool { return $user->hasPermissionTo('manage-categories'); }
}
