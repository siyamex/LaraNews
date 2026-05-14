<?php

namespace App\Policies;

use App\Models\Ad;
use App\Models\User;

class AdPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermissionTo('manage-ads'); }
    public function create(User $user): bool { return $user->hasPermissionTo('manage-ads'); }
    public function update(User $user): bool { return $user->hasPermissionTo('manage-ads'); }
    public function delete(User $user): bool { return $user->hasPermissionTo('manage-ads'); }
}
