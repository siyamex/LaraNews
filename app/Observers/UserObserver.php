<?php

namespace App\Observers;

use App\Jobs\SendWelcomeEmail;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        SendWelcomeEmail::dispatch($user)->delay(now()->addSeconds(10));
    }
}
