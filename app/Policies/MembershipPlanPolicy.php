<?php

namespace App\Policies;

use App\Models\MembershipPlan;
use App\Models\User;

class MembershipPlanPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermissionTo('manage-memberships'); }
    public function create(User $user): bool { return $user->hasPermissionTo('manage-memberships'); }
    public function update(User $user): bool { return $user->hasPermissionTo('manage-memberships'); }
    public function delete(User $user): bool { return $user->hasPermissionTo('manage-memberships'); }
}
